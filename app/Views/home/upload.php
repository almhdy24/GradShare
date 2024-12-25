    <style>
        body {
            background-color: #f4f7fa;
            font-family: 'Arial', sans-serif;
        }
         .container {
             background: white;
            padding: 2rem;
         }
        h1 {
            color: #007bff;
        }
        .form-text.text-muted {
            font-size: 0.9rem;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .progress {
            background-color: #e9ecef;
        }
        .progress-bar {
            background-color: #007bff;
        }
    </style>
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Upload Your Project on GradShare</h1>
        <form id="uploadForm" onsubmit="uploadFile(event)">
            <div class="mb-3">
                <label for="project_name" class="form-label">Project Name</label>
                <input type="text" class="form-control" name="project_name" placeholder="Enter your project name" required>
            </div>
            <div class="mb-3">
                <label for="project_description" class="form-label">Project Description</label>
                <textarea class="form-control" name="project_description" placeholder="Enter your project description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="file" class="form-label">Select File</label>
                <input type="file" class="form-control" name="file" id="file" required multiple webkitdirectory>
                <div class="form-text text-muted">
                    You can choose any files or folders to upload. All selected items will be compressed first and then uploaded securely. Please note that the maximum total file size is 50MB.
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Upload <i class="fas fa-upload"></i></button>
        </form>

        <div id="progress-container" class="mt-3" style="display: none;">
            <div class="progress" style="height: 25px;">
                <div id="progress-bar" class="progress-bar" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <p id="progress-text" class="mt-2 text-center"></p>
        </div>

<div id="success-message" class="mt-3 text-success text-center" style="display: none;">
    <i class="fas fa-check-circle"></i> File uploaded successfully on GradShare!
    <br>
    <button id="goHomeBtn" class="btn btn-primary mt-2">Go Home</button>
    <button id="uploadAnotherBtn" class="btn btn-secondary mt-2">Upload Another</button>
</div>

    </div>
   <script src="<?= $this->env("base_url") ?>assets/js/jszip.js"></script>
<script>
    async function uploadFile(event) {
        event.preventDefault();
        const fileInput = document.querySelector('input[type="file"]').files;
        const zip = new JSZip();
        
        // Add files to the zip
        for (let file of fileInput) {
            zip.file(file.webkitRelativePath || file.name, file);
        }
        
        // Generate the zip file
        const content = await zip.generateAsync({ type: 'blob' });
        
        const formData = new FormData(document.getElementById("uploadForm"));
        const progressContainer = document.getElementById("progress-container");
        const progressText = document.getElementById("progress-text");
        const progressBar = document.getElementById("progress-bar");
        const successMessage = document.getElementById("success-message");
        const errorMessage = document.getElementById("error-message");

   // Initialize UI
               if (progressContainer) {
                   progressContainer.style.display = "block";
               }
               if (successMessage) {
                   successMessage.style.display = "none";
               }
               if (errorMessage) {
                   errorMessage.style.display = "none";
               }
               if (progressBar) {
                   progressBar.style.width = '0%';
               }
               if (progressText) {
                   progressText.innerHTML = '0%';
               }

        // Append the compressed file to FormData
        formData.append('file', content, 'GradShare_compressed.zip');

        // Use XMLHttpRequest to upload with progress tracking
        const xhr = new XMLHttpRequest();

        xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable) {
                const percentComplete = (event.loaded / event.total) * 100;
                progressBar.style.width = percentComplete + '%';
                progressText.innerHTML = Math.round(percentComplete) + '%';
            }
        });

        // Set up the request
        xhr.open("POST", "upload", true);
        xhr.setRequestHeader('Accept', 'application/json');

        // Handling response
        xhr.onload = () => {
 if (xhr.status >= 200 && xhr.status < 300) {
        const result = JSON.parse(xhr.responseText);
        // Show success message
        successMessage.style.display = "block";
        // Hide Form
        document.getElementById("uploadForm").remove();
       //hide progressBar
        progressContainer.style.display = "none";
        // Attach event listeners to buttons
        document.getElementById("goHomeBtn").addEventListener("click", function() {
            // Navigate to the home page
            window.location.href = '/';
        });

        document.getElementById("uploadAnotherBtn").addEventListener("click", function() {
            // Reload the page to allow for another upload
            location.reload();
        });
    } else {
                // Show error message
                errorMessage.innerHTML = `Upload failed with status: ${xhr.status}`;
                errorMessage.style.display = "block";
            }
        };

        // Handle errors
        xhr.onerror = () => {
            errorMessage.innerHTML = 'An error occurred during the upload.';
            errorMessage.style.display = "block";
        };

        // Send the FormData
        xhr.send(formData);
    }
</script>