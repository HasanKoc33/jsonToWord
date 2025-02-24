<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JSON to Word Converter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 1200px;
        }
        .main-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            margin-top: 2rem;
            margin-bottom: 2rem;
        }
        .json-input-area {
            min-height: 300px;
            font-family: monospace;
            resize: vertical;
        }
        .json-tree {
            max-height: 500px;
            overflow-y: auto;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: #f8f9fa;
        }
        .json-tree .list-group-item {
            border: none;
            padding: 0.5rem 1rem;
            margin-bottom: 2px;
            background: transparent;
            cursor: pointer;
        }
        .json-tree .list-group-item:hover {
            background: #e9ecef;
        }
        .selected-item {
            background-color: #e3f2fd !important;
            border-left: 4px solid #0d6efd !important;
        }
        .btn-area {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 1rem 0;
            border-top: 1px solid #dee2e6;
            margin-top: 1rem;
        }
        .file-input-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .file-input-area:hover {
            border-color: #0d6efd;
            background: #f8f9fa;
        }
        .file-input-area input[type="file"] {
            display: none;
        }
        .json-files-list {
            margin: 1rem 0;
            padding: 0;
            list-style: none;
        }
        .json-file-item {
            display: flex;
            align-items: center;
            padding: 8px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
        }

        .file-checkbox {
            margin-right: 10px;
        }

        .file-name {
            flex-grow: 1;
            margin: 0;
            cursor: pointer;
        }

        .remove-file {
            cursor: pointer;
            color: #dc3545;
            font-weight: bold;
            margin-left: 10px;
        }

        .remove-file:hover {
            color: #c82333;
        }

        .btn-area {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        /* Kılavuz ve Özellikler Kartları Stilleri */
        .guide-card, .features-card {
            height: 100%;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .guide-list {
            padding-left: 20px;
        }

        .guide-list li {
            margin-bottom: 20px;
        }

        .guide-list li strong {
            color: #0d6efd;
            display: block;
            margin-bottom: 5px;
        }

        .features-list {
            list-style: none;
            padding: 0;
        }

        .features-list li {
            margin-bottom: 20px;
            padding-left: 25px;
            position: relative;
        }

        .features-list li i {
            position: absolute;
            left: 0;
            top: 4px;
        }

        .features-list li strong {
            color: #198754;
            display: block;
            margin-bottom: 5px;
        }

        .features-list li p {
            margin: 0;
            color: #6c757d;
        }

        .card-header i {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-card p-4">
            <h2 class="mb-4 text-center">JSON to Word Converter</h2>
            
            <form id="jsonForm">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="file-input-area w-100" id="dropZone">
                                <input type="file" id="jsonFile" accept=".json">
                                <div>
                                    <i class="fas fa-file-upload fs-3 mb-2"></i>
                                    <p class="mb-0">JSON dosyasını sürükleyin veya seçin</p>
                                </div>
                            </label>

                            <div id="jsonFilesList" class="json-files-list">
                                <!-- JSON dosyaları buraya listelenecek -->
                            </div>
                            
                            <input type="hidden" id="jsonInput" name="jsonInput">
                            <input type="hidden" id="selectedPath" name="selectedPath">
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="selectedPath" class="form-label">Seçilen Özellik Yolu:</label>
                            <input type="text" class="form-control" id="selectedPathDisplay" readonly>
                        </div>

                        <div class="json-tree" id="jsonTree">
                            <!-- JSON ağacı burada gösterilecek -->
                        </div>
                    </div>
                </div>

                <div class="btn-area text-center">
                    <button type="button" class="btn btn-primary btn-lg" onclick="convertSelectedFile()" disabled id="convertBtn">
                        <i class="fas fa-file-word"></i> Seçili Dosyayı Word'e Dönüştür
                    </button>
                  
                </div>
            </form>
        </div>
    </div>

    <!-- Kullanım Kılavuzu Bölümü -->
    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-6">
                <div class="card guide-card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-book"></i> Kullanım Kılavuzu</h4>
                    </div>
                    <div class="card-body">
                        <ol class="guide-list">
                            <li>
                                <strong>JSON Dosyası Yükleme</strong>
                                <p>Dosyaları sürükleyip bırakarak veya "JSON dosyasını sürükleyin veya seçin" alanına tıklayarak yükleyebilirsiniz.</p>
                            </li>
                            <li>
                                <strong>Çoklu Dosya Seçimi</strong>
                                <p>Birden fazla JSON dosyasını checkbox'ları işaretleyerek seçebilirsiniz. Seçili dosyalar otomatik olarak birleştirilecektir.</p>
                            </li>
                            <li>
                                <strong>Özellik Seçimi</strong>
                                <p>Sağ taraftaki JSON ağacından istediğiniz özelliği seçerek Word belgesine aktarılacak veriyi belirleyebilirsiniz.</p>
                            </li>
                            <li>
                                <strong>Word'e Dönüştürme</strong>
                                <p>"Seçili Dosyayı Word'e Dönüştür" butonuna tıklayarak seçili verileri Word belgesine aktarabilirsiniz.</p>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card features-card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-star"></i> Özellikler</h4>
                    </div>
                    <div class="card-body">
                        <ul class="features-list">
                            <li>
                                <i class="fas fa-check text-success"></i>
                                <strong>Çoklu Dosya Desteği</strong>
                                <p>Birden fazla JSON dosyasını aynı anda işleyebilme</p>
                            </li>
                            <li>
                                <i class="fas fa-check text-success"></i>
                                <strong>Otomatik Birleştirme</strong>
                                <p>Seçili JSON dosyalarını otomatik olarak tek bir liste halinde birleştirme</p>
                            </li>
                            <li>
                                <i class="fas fa-check text-success"></i>
                                <strong>Kolay Gezinme</strong>
                                <p>JSON verilerini ağaç yapısında görüntüleme ve kolay seçim yapabilme</p>
                            </li>
                            <li>
                                <i class="fas fa-check text-success"></i>
                                <strong>Sürükle-Bırak</strong>
                                <p>Dosyaları sürükleyip bırakarak hızlı yükleme</p>
                            </li>
                            <li>
                                <i class="fas fa-check text-success"></i>
                                <strong>Özelleştirilebilir Çıktı</strong>
                                <p>İstediğiniz JSON özelliklerini seçerek özelleştirilmiş Word belgeleri oluşturma</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        let jsonFiles = new Map(); // Dosya adı ve içeriğini saklayacak Map
        let selectedFileName = null; // Seçili dosya adını tutacak değişken

        // JSON dosyası yükleme işlemi
        document.getElementById('jsonFile').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    try {
                        // JSON formatını kontrol et
                        JSON.parse(e.target.result);
                        
                        // Dosyayı Map'e ekle
                        jsonFiles.set(file.name, e.target.result);
                        
                        // Dosya listesini güncelle
                        updateFilesList();
                    } catch (error) {
                        alert('Geçersiz JSON formatı: ' + error.message);
                    }
                };
                reader.readAsText(file);
            }
        });

        function updateFilesList() {
            const filesList = document.getElementById('jsonFilesList');
            filesList.innerHTML = '';
            
            jsonFiles.forEach((content, fileName) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'json-file-item';
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'file-checkbox me-2';
                checkbox.id = `checkbox-${fileName}`;
                checkbox.onchange = () => updateSelectedFiles();
                
                const label = document.createElement('label');
                label.htmlFor = `checkbox-${fileName}`;
                label.className = 'file-name';
                label.textContent = fileName;
                
                const removeBtn = document.createElement('span');
                removeBtn.className = 'remove-file';
                removeBtn.innerHTML = '&times;';
                removeBtn.onclick = () => removeFile(fileName);
                
                fileItem.appendChild(checkbox);
                fileItem.appendChild(label);
                fileItem.appendChild(removeBtn);
                filesList.appendChild(fileItem);
            });

            // Birleştirme butonunu göster/gizle
            document.getElementById('mergeBtn').style.display = jsonFiles.size > 1 ? 'inline-block' : 'none';
            document.getElementById('convertBtn').disabled = true;
        }

        function updateSelectedFiles() {
            const selectedCheckboxes = document.querySelectorAll('.file-checkbox:checked');
            if (selectedCheckboxes.length > 0) {
                const mergedData = [];
                selectedCheckboxes.forEach(checkbox => {
                    const fileName = checkbox.id.replace('checkbox-', '');
                    const jsonData = JSON.parse(jsonFiles.get(fileName));
                    if (Array.isArray(jsonData)) {
                        mergedData.push(...jsonData);
                    } else {
                        mergedData.push(jsonData);
                    }
                });
                displayJsonTree(mergedData);
                document.getElementById('convertBtn').disabled = false;
            } else {
                document.getElementById('jsonTree').innerHTML = '';
                document.getElementById('convertBtn').disabled = true;
            }
        }

        function removeFile(fileName) {
            jsonFiles.delete(fileName);
            updateFilesList();
        }

        function convertSelectedFile() {
            const selectedCheckboxes = document.querySelectorAll('.file-checkbox:checked');
            if (selectedCheckboxes.length === 0 || !document.getElementById('selectedPath').value) {
                alert('Lütfen en az bir dosya ve özellik seçin');
                return;
            }

            // Seçili dosyaları birleştir
            const mergedData = [];
            const selectedFileNames = [];
            selectedCheckboxes.forEach(checkbox => {
                const fileName = checkbox.id.replace('checkbox-', '');
                selectedFileNames.push(fileName);
                const jsonData = JSON.parse(jsonFiles.get(fileName));
                if (Array.isArray(jsonData)) {
                    mergedData.push(...jsonData);
                } else {
                    mergedData.push(jsonData);
                }
            });

            // Birleştirilmiş veriyi process.php'ye gönder
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'process.php';

            const jsonInput = document.createElement('input');
            jsonInput.type = 'hidden';
            jsonInput.name = 'jsonInput';
            jsonInput.value = JSON.stringify(mergedData);

            const pathInput = document.createElement('input');
            pathInput.type = 'hidden';
            pathInput.name = 'selectedPath';
            pathInput.value = document.getElementById('selectedPath').value;

            const fileNameInput = document.createElement('input');
            fileNameInput.type = 'hidden';
            fileNameInput.name = 'fileName';
            fileNameInput.value = selectedFileNames.join('_and_');

            form.appendChild(jsonInput);
            form.appendChild(pathInput);
            form.appendChild(fileNameInput);
            document.body.appendChild(form);
            form.submit();
        }

        function mergeSelectedFiles() {
            const selectedCheckboxes = document.querySelectorAll('.file-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('Lütfen en az bir dosya seçin');
                return;
            }

            // Seçili dosyaları birleştir
            const mergedData = [];
            selectedCheckboxes.forEach(checkbox => {
                const fileName = checkbox.id.replace('checkbox-', '');
                const jsonData = JSON.parse(jsonFiles.get(fileName));
                if (Array.isArray(jsonData)) {
                    mergedData.push(...jsonData);
                } else {
                    mergedData.push(jsonData);
                }
            });

            // Birleştirilmiş verileri JSON dosyası olarak indir
            const jsonStr = JSON.stringify(mergedData);
            const blob = new Blob([jsonStr], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'merged_data.json';
            a.click();
        }

        // Sürükle-bırak işlemi
        const dropZone = document.getElementById('dropZone');
        
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.backgroundColor = '#e9ecef';
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.style.backgroundColor = '';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.backgroundColor = '';
            
            const file = e.dataTransfer.files[0];
            if (file && file.type === "application/json") {
                document.getElementById('jsonFile').files = e.dataTransfer.files;
                document.getElementById('jsonFile').dispatchEvent(new Event('change'));
            } else {
                alert('Lütfen sadece JSON dosyası yükleyin.');
            }
        });

        // JSON ağacını görüntüleme
        function displayJsonTree(data, path = '') {
            let html = '<ul class="list-group">';
            
            if (Array.isArray(data)) {
                data.forEach((item, index) => {
                    const currentPath = path + `[${index}]`;
                    if (typeof item === 'object' && item !== null) {
                        html += `<li class="list-group-item">Array[${index}]`;
                        html += displayJsonTree(item, currentPath);
                        html += '</li>';
                    } else {
                        const safeValue = typeof item === 'string' ? item.replace(/"/g, '&quot;') : item;
                        html += `<li class="list-group-item" onclick="selectPath('${currentPath}', this)">${safeValue}</li>`;
                    }
                });
            } else if (typeof data === 'object' && data !== null) {
                Object.keys(data).forEach(key => {
                    const currentPath = path ? `${path}.${key}` : key;
                    const value = data[key];
                    if (typeof value === 'object' && value !== null) {
                        html += `<li class="list-group-item">${key}`;
                        html += displayJsonTree(value, currentPath);
                        html += '</li>';
                    } else {
                        const safeValue = typeof value === 'string' ? value.replace(/"/g, '&quot;') : value;
                        html += `<li class="list-group-item" onclick="selectPath('${currentPath}', this)">${key}: ${safeValue}</li>`;
                    }
                });
            }
            
            html += '</ul>';
            if (!path) {
                document.getElementById('jsonTree').innerHTML = html;
            }
            return html;
        }

        // Yol seçme işlemi
        function selectPath(path, element) {
            // Önceki seçili öğeyi temizle
            const previousSelected = document.querySelector('.selected-item');
            if (previousSelected) {
                previousSelected.classList.remove('selected-item');
            }
            
            // Yeni öğeyi seç
            element.classList.add('selected-item');
            document.getElementById('selectedPath').value = path;
            document.getElementById('selectedPathDisplay').value = path;
            document.getElementById('convertBtn').disabled = false;
        }
    </script>
</body>
</html>
