/**
 * File Upload
 */

'use strict';

(function () {
  // previewTemplate: Updated Dropzone default previewTemplate
  // ! Don't change it unless you really know what you are doing
  const previewTemplate = `<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;

  // ? Start your code from here

  // Basic Dropzone
  // --------------------------------------------------------------------
  const dropzoneBasic = document.querySelector('#dropzone-basic');
  if (dropzoneBasic) {
    const myDropzone = new Dropzone(dropzoneBasic, {
      previewTemplate: previewTemplate,
      parallelUploads: 1,
      maxFilesize: 5,
      addRemoveLinks: true,
      maxFiles: 1
    });
  }

  // Multiple Dropzone
  // --------------------------------------------------------------------
  const dropzoneMulti = document.querySelector('#dropzone-multi');
  if (dropzoneMulti) {
    const myDropzoneMulti = new Dropzone(dropzoneMulti, {
        url: dropzoneMulti.action, // Use the form's action URL
        paramName: "file", // Matches the name attribute in the form
        previewTemplate: previewTemplate,
        parallelUploads: 1,
        maxFilesize: 5, // Max file size in MB
        addRemoveLinks: true,
        autoProcessQueue: true, // Automatically upload files
    });

    // Add CSRF token to the request
    myDropzoneMulti.on("sending", function (file, xhr, formData) {
        formData.append("_token", document.querySelector('input[name="_token"]').value);
    });

    // Update hidden input on successful upload
    myDropzoneMulti.on("success", function (file, response) {
        console.log("File uploaded successfully:", response);
        // Assuming the server returns the file path in `response.path`
        document.querySelector('#filew').value = response.path;
    });

    myDropzoneMulti.on("error", function (file, errorMessage) {
        console.error("Error uploading file:", errorMessage);
    });
  }
})();
