<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Registration Number</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: url("images/background.jpg") no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
        }
        .header {
            width: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 20px;
            box-sizing: border-box;
            position: relative;
        }
        .header h1 {
            margin: 0;
            font-size: 30px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
        }
        .header .fee-receipt {
            font-size: 16px;
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            text-align:center;
        }
        .header .fee-receipt:hover {
            background-color: #0056b3;
        }
        .aform {
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            padding: 20px;
            color: white;
            text-align: center;
            margin-top: 20px;
        }
        .aform input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .aform button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .aform button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Fee Receipt System</h1>
        <a href="admin/add_excel.php" class="fee-receipt">Add Excel</a>
    </div>
    <div class="aform">
        <h2>Search Registration Number</h2>
        
        <form action="search.php" method="POST">
            <div class="form-group">
                <label for="reg_no">Registration Number:</label>
                <input type="text" id="reg_no" name="reg_no" required>
            </div>
            <button type="submit">Search</button>
        </form>
    </div>
</body>
</html>
