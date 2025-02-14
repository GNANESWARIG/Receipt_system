<?php
session_start();
include("db.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt System</title>
    <style>
        body {
            background-image: url("background.jpg");
            background-repeat: no-repeat;
            background-size: 100% 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .header {
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            position: absolute;
            top: 0;
            left: 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        .header .back-link {
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        .header .back-link:hover {
            background-color: #0056b3;
        }
        .form-container {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .form-container input,
        .form-container button {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container button {
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .form-container .download-template {
            margin-top: 10px;
            background-color: #28a745;
            color: white;
        }
        .form-container .download-template:hover {
            background-color: #218838;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
    </head>
    <body>
    <div class="header">
        <h1>Add Excel</h1>
        <a href=".../index.php" class="back-link">Back to Home</a>
    </div>
    <div class="form-container">
        <h2>Upload Student Data</h2>
        <form action="averify.php" method="POST" enctype="multipart/form-data">
            <label for="file">Choose Excel File:</label>
            <input type="file" name="myfile" id="file" accept=".xlsx" required>
            <button type="submit" name="uploadfile" value="upload" class="mt-3 btn btn-primary mx-auto px-4 d-block">Upload</button>
        </form>
        <a href="https://docs.google.com/spreadsheets/d/1yJIdTPLxkyj_HPya6DbiVMd8h5ePmGkk/export?format=xlsx" target="_blank">
            <button class="download-template">Download Template</button>
        </a>
    </div>
    </body>
</html>
