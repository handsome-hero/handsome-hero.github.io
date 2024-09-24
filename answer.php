<?php
// 连接到MySQL数据库
$servername = "localhost";
$username = "root";
$password = "Wushuhong2003"; // 如果有密码，请填写密码
$dbname = "test";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 查询数据
$sql1 = "SELECT Year, Vehicle_Principal_Payment FROM Vehicle_Principal_Payment";
$result1 = $conn->query($sql1);

$sql2 = "SELECT Year, Interest_Payment FROM Interest_Payment";
$result2 = $conn->query($sql2);

$sql3 = "SELECT Year, Insurance_Total FROM Insurance_Total";
$result3 = $conn->query($sql3);

$sql4 = "SELECT Type,Size,Use_Purpose,Model_Year,Age, Energy_Cost FROM Energy_Cost";
$result4 = $conn->query($sql4);

$sql5 = "SELECT Age, Year_Vehicle_Maintenance_Cost FROM Year_Vehicle_Maintenance_Cost";
$result5 = $conn->query($sql5);

$sql6 = "SELECT Age, Vehicle_Repair_Cost FROM Vehicle_Repair_Cost";
$result6 = $conn->query($sql6);

$sql7 = "SELECT Age, Tax, Fees FROM Tax_Fees";
$result7 = $conn->query($sql7);

$sql8 = "SELECT Type, Size,  Model_Year, Age, Public_charg_availability,Fast_charg_Probability,Slow_charg_Probability,Slow_charging_power,Trip_time_public_chargers,Trip_time_public_H2_stations,Fast_charging_power FROM Infrastructure_Data" ;
$result8 = $conn->query($sql8);

$sql9 = "SELECT Type, Size, Use_Purpose, Powertrain, Model_Year, Age, Select_Relative_Repair_Cost_Factor FROM Select_Relative_Repair_Cost_Factor";
$result9 = $conn->query($sql9);




// 准备输出数据
$response = array();
$response1 = array();
$chartData = array();

$totalCosts = [
    'Vehicle' => 0,
    'Financing' => 0,
    'Insurance' => 0,
    'Fuel' => 0,
    'Maintenance' => 0,
    'Repair' => 0,
    'Tax' => 0,
    'Fees' => 0
];


if ($result1->num_rows > 0) {
    $chartData = array();
    while($row = $result1->fetch_assoc()) {
        $chartData[] = array(
            "Year" => $row["Year"],
            "Vehicle_Principal_Payment" => $row["Vehicle_Principal_Payment"]
        );
        $totalCosts['Vehicle'] += $row["Vehicle_Principal_Payment"];
    }
    $response['Vehicle_Principal_PaymentData'] = $chartData;
}

if ($result2->num_rows > 0) {
    $chartData = array();
    while($row = $result2->fetch_assoc()) {
        $chartData[] = array(
            "Year" => $row["Year"],
            "Interest_Payment" => $row["Interest_Payment"]
        );
        $totalCosts['Financing'] += $row["Interest_Payment"];
    }
    $response['Interest_PaymentData'] = $chartData;
}

if ($result3->num_rows > 0) {
    $chartData = array();
    while($row = $result3->fetch_assoc()) {
        $chartData[] = array(
            "Year" => $row["Year"],
            "Insurance_Total" => $row["Insurance_Total"]
        );
        $totalCosts['Insurance'] += $row["Insurance_Total"];
    }
    $response['Insurance_TotalData'] = $chartData;
}

if ($result4->num_rows > 0) {
    $Energy_CostData1 .= "<h2>Select Energy_Cost</h2>";
    $Energy_CostData1.= "<table border='1'>
                    <tr>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Use Purpose</th>
                        <th>Model Year</th>
                        <th>Age</th>
                        <th>Energy_Cost</th>
                    </tr>";
    // 输出每行数据
    $chartData = array();
    while($row = $result4->fetch_assoc()) {
        $Energy_CostData1 .= "<tr>
                        <td>".$row["Type"]."</td>
                        <td>".$row["Size"]."</td>
                        <td>".$row["Use_Purpose"]."</td>
                        <td>".$row["Model_Year"]."</td>
                        <td>".$row["Age"]."</td>
                        <td>".$row["Energy_Cost"]."</td>
                      </tr>";
        $chartData[] = array(
            "Age" => $row["Age"],
            "Energy_Cost" => $row["Energy_Cost"]
        );
        $totalCosts['Fuel'] += $row["Energy_Cost"];

    }
    $Energy_CostData1 .= "</table>";

    $response['Energy_CostData'] = $chartData;
    $response['Energy_CostData1'] = $Energy_CostData1;
}

if ($result5->num_rows > 0) {
    $chartData = array();
    while($row = $result5->fetch_assoc()) {
        $chartData[] = array(
            "Age" => $row["Age"],
            "Year_Vehicle_Maintenance_Cost" => $row["Year_Vehicle_Maintenance_Cost"]
        );
        $totalCosts['Maintenance'] += $row["Year_Vehicle_Maintenance_Cost"];
    }
    $response['Year_Vehicle_Maintenance_CostData'] = $chartData;
}

if ($result6->num_rows > 0) {
    $chartData = array();
    while($row = $result6->fetch_assoc()) {
        $chartData[] = array(
            "Age" => $row["Age"],
            "Vehicle_Repair_Cost" => $row["Vehicle_Repair_Cost"]
        );
        $totalCosts['Repair'] += $row["Vehicle_Repair_Cost"];
    }
    $response['Vehicle_Repair_CostData'] = $chartData;
}

if ($result7->num_rows > 0) {
    $taxData = array();
    $feesData = array();
    while($row = $result7->fetch_assoc()) {
        $age = $row["Age"];
        $taxData[] = array(
            "Age" => $age,
            "Tax" => $row["Tax"]
        );
        $feesData[] = array(
            "Age" => $age,
            "Fees" => $row["Fees"]
        );
        $totalCosts['Tax'] += $row["Tax"];
        $totalCosts['Fees'] += $row["Fees"];
    }
    $response['TaxData'] = $taxData;
    $response['FeesData'] = $feesData;
}

if ($result8->num_rows > 0) {
    $Infrastructure_DataData .= "<h2>Select Infrastructure_Data</h2>";
    $Infrastructure_DataData .= "<table border='1'>
                    <tr>
                        <th>Type</th>
                        <th>Size</th>
                        <th>Model_Year</th>
                        <th>Age</th>
                        <th>Public_charg_availability</th>
                        <th>Fast_charg_Probability</th>
                        <th>Slow_charg_Probability</th>
                        <th>Slow_charging_power</th>
                        <th>Trip_time_public_chargers</th>
                        <th>Trip_time_public_H2_stations</th>
                        <th>Fast_charging_power</th>
                    </tr>";
    // 输出每行数据
    while($row = $result8->fetch_assoc()) {
        $Infrastructure_DataData .= "<tr>
                        <td>".$row["Type"]."</td>
                        <td>".$row["Size"]."</td>
                        <td>".$row["Model_Year"]."</td>
                        <td>".$row["Age"]."</td>
                        <td>".$row["Public_charg_availability"]."</td>
                        <td>".$row["Fast_charg_Probability"]."</td>
                        <td>".$row["Slow_charg_Probability"]."</td>
                        <td>".$row["Slow_charging_power"]."</td>
                        <td>".$row["Trip_time_public_chargers"]."</td>
                        <td>".$row["Trip_time_public_H2_stations"]."</td>
                        <td>".$row["Fast_charging_power"]."</td>
                      </tr>";

    }
    $Infrastructure_DataData .= "</table>";
    $response["Infrastructure_DataData"]=$Infrastructure_DataData;
} 


if ($result9->num_rows > 0) {
    $Select_Relative_Repair_Cost_FactorchartData=array();
    // 输出每行数据
    while($row = $result9->fetch_assoc()) {

        $Select_Relative_Repair_Cost_FactorchartData[] = array(
            "Age" => $row["Age"],
            "Select_Relative_Repair_Cost_Factor" => $row["Select_Relative_Repair_Cost_Factor"]
        );
    }
    $response["Select_Relative_Repair_Cost_FactorchartData"]=$Select_Relative_Repair_Cost_FactorchartData;
} 

$response['TotalCosts'] = $totalCosts;

// 关闭连接
$conn->close();

// 返回JSON数据
header('Content-Type: application/json');
echo json_encode($response);
?>