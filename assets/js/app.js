/*
 * Welcome to your app"s main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import "../css/app.scss";

document.getElementById("file-upload").onchange = function () {
    // Apply data to spans to inform the user
    document.getElementById("file-name").innerText = "Filename: " + this.files.item(0).name;
    document.getElementById("file-size").innerText = "File size: " + (this.files.item(0).size / 1024 / 1024) + "MB";
    document.getElementById("file-type").innerText = "File type: " + this.files.item(0).type;
};