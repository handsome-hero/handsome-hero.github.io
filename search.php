<?php
// 开启错误报告
error_reporting(E_ALL);
echo "search.php is called and executed.";
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 输出收到的表单内容
    echo "Received form data:<br>";
    echo "Type: " . $_POST['type'] . "<br>";
    echo "Size: " . $_POST['size'] . "<br>";
    echo "Use Purpose: " . $_POST['use_purpose'] . "<br>";
    echo "Powertrain: " . $_POST['powertrain'] . "<br>";
    echo "Fuel Type: " . $_POST['fuel_type'] . "<br>";
    echo "Model Year: " . $_POST['model_year'] . "<br>";

    // 连接到数据库
    $servername = "localhost";
    $username = "root";
    $password = "Wushuhong2003"; // 如果有密码，请填写密码
    $dbname = "test";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        // 输出连接成功信息
        echo "Connected successfully to the database<br>";
    }

    // 插入数据到 General_Information 表
    $sql_insert = "INSERT INTO General_Information (Type, Size, Use_Purpose, Powertrain, Fuel_Type, Model_Year)
                   VALUES ('{$_POST['type']}', '{$_POST['size']}', '{$_POST['use_purpose']}', '{$_POST['powertrain']}', '{$_POST['fuel_type']}', '{$_POST['model_year']}')";

    if ($conn->query($sql_insert) === TRUE) {
        echo "New record created successfully<br>";
    } else {
        echo "Error: " . $sql_insert . "<br>" . $conn->error . "<br>";
    }

    // 查询并打印 CVKT_and_Daily_Mean 表的所有数据
    $sql_cvkt = "SELECT * FROM CVKT_and_Daily_Mean";
    $result_cvkt = $conn->query($sql_cvkt);

    if ($result_cvkt === false) {
        // 输出数据库查询错误信息
        echo "Query error: " . $conn->error . "<br>";
    } else {
        if ($result_cvkt->num_rows > 0) {
            // 输出数据
            while($row = $result_cvkt->fetch_assoc()) {
                echo "Type: " . $row["Type"]. " - Size: " . $row["Size"]. " - Use Purpose: " . $row["Use_Purpose"]. " - Year: " . $row["Year"]. " - Cumulative VKT: " . $row["Cumulative_VKT"]. " - Daily Mean: " . $row["Daily_Mean"]. "<br>";
            }
        } else {
            echo "No data found in CVKT_and_Daily_Mean.<br>";
        }
    }

    $conn->close();
}
?>