<?php
require 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jsonInput = $_POST['jsonInput'];
    $selectedPath = $_POST['selectedPath'];
    
    // JSON verisini parse et
    $data = json_decode($jsonInput, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        die("JSON parse hatası: " . json_last_error_msg());
    }

    // Seçilen yolu parçala ve array indekslerini ayır
    $pathParts = [];
    $path = str_replace('][', '].[', $selectedPath);
    $parts = explode('.', $path);
    
    // Yol analizi yap ve sayısal indeksleri işaretle
    $pathAnalysis = [];
    foreach ($parts as $part) {
        if (preg_match('/\[(\d+)\]/', $part, $matches)) {
            // Array indeksi - bunu [] olarak değiştireceğiz
            $part = preg_replace('/\[\d+\]/', '', $part);
            if ($part) {
                $pathAnalysis[] = ['type' => 'key', 'value' => $part];
            }
            $pathAnalysis[] = ['type' => 'array', 'value' => '*'];
        } else {
            $pathAnalysis[] = ['type' => 'key', 'value' => $part];
        }
    }

    // Path parçalarını oluştur
    foreach ($pathAnalysis as $item) {
        $pathParts[] = $item['value'];
    }

    $selectedValues = [];

    // Veriyi gezin ve seçilen değerleri topla
    function traverseArray($data, $pathParts, &$selectedValues) {
        if (empty($pathParts)) {
            if (!is_null($data)) {
                $selectedValues[] = $data;
            }
            return;
        }

        $currentKey = array_shift($pathParts);
        
        if ($currentKey === '*') {
            // Tüm array elemanlarını dolaş
            if (is_array($data)) {
                foreach ($data as $item) {
                    $newPathParts = $pathParts;
                    traverseArray($item, $newPathParts, $selectedValues);
                }
            }
        } else {
            // Normal key
            if (is_array($data)) {
                if (isset($data[$currentKey])) {
                    traverseArray($data[$currentKey], $pathParts, $selectedValues);
                }
            }
        }
    }

    traverseArray($data, $pathParts, $selectedValues);

    // Word dökümanı oluştur
    $phpWord = new PhpWord();
    
    // Varsayılan font ve stil ayarları
    $phpWord->setDefaultFontName('Arial');
    $phpWord->setDefaultFontSize(11);
    
    // Yeni bir section ekle
    $section = $phpWord->addSection();
    
    // Stil tanımlamaları
    $titleStyle = array(
        'bold' => true,
        'size' => 14,
        'name' => 'Arial',
        'color' => '000000'
    );
    
    $pathStyle = array(
        'bold' => true,
        'size' => 12,
        'name' => 'Arial',
        'color' => '0000FF'
    );
    
    $normalStyle = array(
        'size' => 11,
        'name' => 'Arial'
    );

    // Seçilen değerleri ekle
    foreach ($selectedValues as $value) {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        
        // Null kontrolü
        if (is_null($value)) {
            $value = "null";
        }
        
        // Boolean değerleri string'e çevir
        if (is_bool($value)) {
            $value = $value ? "true" : "false";
        }
        
        // Sayıları string'e çevir
        if (is_numeric($value)) {
            $value = (string)$value;
        }

        // Metni temizle ve formatla
        $title = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $title = preg_replace('/\s+/', ' ', $title);
        $title = trim($title);
        
        $section->addText($title, $normalStyle);
        $section->addTextBreak();
    }

    try {
        // Geçici dosya oluştur
        $tempFile = tempnam(sys_get_temp_dir(), 'word');
        
        // Word dosyasını geçici dosyaya kaydet
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($tempFile);
        
        // Dosya içeriğini oku
        $content = file_get_contents($tempFile);
        
        // Geçici dosyayı sil
        unlink($tempFile);
        
        // Header'ları ayarla
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        
        // JSON dosya adını al ve .json uzantısını kaldır
        $jsonFileName = isset($_POST['fileName']) ? $_POST['fileName'] : 'output';
        $wordFileName = pathinfo($jsonFileName, PATHINFO_FILENAME);
        
        header('Content-Disposition: attachment; filename="' . $wordFileName . '.docx"');
        header('Cache-Control: max-age=0');
        
        // İçeriği gönder
        echo $content;
        exit;
    } catch (Exception $e) {
        die("Dosya oluşturulurken hata oluştu: " . $e->getMessage());
    }
}
?>
