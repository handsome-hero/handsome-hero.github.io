<?php
// 连接到MySQL数据库
$servername = "localhost";
$username = "root";
$password = "Wushuhong2003"; // 如果有密码，请填写密码
$dbname = "test";

// 创建连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 获取表单类型
$formType = isset($_POST['form_type']) ? $_POST['form_type'] : '';

$response = [];
$form_type = $_POST['form_type'];

$type = $_POST['type'];
$size = $_POST['size'];
$use_purpose = $_POST['use_purpose'];
$powertrain = $_POST['powertrain'];
$fuel_type = $_POST['fuel_type'];
$model_year = $_POST['model_year'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && $formType == 'general_info') {
    // 删除表格数据的函数
    function deleteTableData($conn, $table) {
        $sql_delete = "DELETE FROM $table";
        if ($conn->query($sql_delete) !== TRUE) {
            return "Error deleting data from $table: " . $conn->error;
        }
        return null;
    }

    // 表名数组
    $tables = [
        "General_Information", "VKT","CVKT_and_Daily_Mean","Select_Max_CVKT", "Select_AVKT", "Select_Daily_Mode", "Operating_Days",  "Gamma_Scale_Beta", "Gamma_Shape_Alpha", "Infrastructure", "Infrastructure_Data","Energy_Price",
        "Energy_Consumption_Rate_Exaggerate_Factor_Value","Energy_Consumption_Rate_Exaggerate_Factor",
        "Electric_Or_Hydrogen_Driving_Range","Select_Relative_Repair_Cost_Factor" ,"Select_Vehicle_Maintenance_Cost_Age","Vehicle_Maintenance_Cost","OnRoadExpenditure", "PF", "UF", "Daily_Fuel_VKT", "Daily_Electricity_Hydrogen_VKT", "Energy_Cost","Select_Year_Fuel_Rate","Select_Repairment", "Select_Relative_Maintenance_Cost","Year_Vehicle_Maintenance_Cost","Vehicle_Repair_Cost","Vehicle_Maintenance_Cost_Age1","Price_per_service",
        "Financing_Loan_Rate_Setup", "Loan_Rate_Downpayment_Ratio",
        "Government_Subsidies_Setup", "Select_Year_Fuel_Rate1","Select_Max_subsidies","Subsidies_Battery_Size","Total_Subsidies","Subsidies_Subsidies","Subsidies",
        "Vehicle_Purchase_Price_Setup","Select_Year_Fuel_Rate2","Extra_Price","Vehicle_Purchase_Price","Purchase_Price","Vehicle_Downpayment","Vehicle_Loan","PMT","Interest_Payment","Vehicle_Principal_Payment","Total_Payment","Vehicle_Loan_Left", "Tax_Fee", "Select_Cost","Select_Tax_Multiplier","Tax_Fees","MSRP","Residual_Value_Year","Relative_Residual_Value","Residual_Value","Depreciation_Cost","Car_Damage_Insurance","Insurance_Total",
        "Condition_Trend","Subsidies_Composite","Purchase_Price_Composite","Vehicle_Composite","Financing_Composite","Insurance_Composite","Fuel_Composite","Maintenance_Composite","Repair_Composite","Tax_Fee_Composite"


    ];

    // 删除每个表的数据
    foreach ($tables as $table) {
        $deleteError = deleteTableData($conn, $table);
        if ($deleteError) {
            $response['errors'][] = $deleteError;
        }
    }

    // 插入数据到General_Information表
    $sql_insert = "INSERT INTO General_Information (Type, Size, Use_Purpose, Powertrain, Fuel_Type, Model_Year)
                   VALUES ('$type', '$size', '$use_purpose', '$powertrain', '$fuel_type', '$model_year')";

    if ($conn->query($sql_insert) === TRUE) {
        $response['messages'][] = "New record created successfully";
    } else {
        $response['errors'][] = "Error: " . $sql_insert . " - " . $conn->error;
    }
} elseif ($form_type == 'energy_factor') {
    $energy_consumption_rate_exaggerate_factor = $_POST['energy_consumption_rate_exaggerate_factor'];
    $factor_value = $_POST['factor_value'];

    // 插入Energy_Consumption_Rate_Exaggerate_Factor_Value数据
    $sql = "INSERT INTO Energy_Consumption_Rate_Exaggerate_Factor_Value (Type, Size, Use_Purpose, Powertrain, Fuel_Type, Model_Year, Energy_Consumption_Rate_Exaggerate_Factor, Factor_Value)
    VALUES ('$type', '$size', '$use_purpose', '$powertrain', '$fuel_type', '$model_year', '$energy_consumption_rate_exaggerate_factor', '$factor_value')";

} elseif ($form_type == 'electric_info') {
    $electric_range = $_POST['electric_range'];
    $maintenance_cost = $_POST['maintenance_cost'];

    // 插入Electric_Or_Hydrogen_Driving_Range数据
    $sql = "INSERT INTO Electric_Or_Hydrogen_Driving_Range (Type, Size, Use_Purpose, Powertrain, Fuel_Type, Model_Year, Electric_Or_Hydrogen_Driving_Range, Maintenance_Cost_Scenario)
    VALUES ('$type', '$size', '$use_purpose', '$powertrain', '$fuel_type', '$model_year', '$electric_range', '$maintenance_cost')";
} elseif ($form_type == 'financing') {
    $financing_loan_rate_setup = $_POST['financing_loan_rate_setup'];
    $customized_loan_rate_input = $_POST['customized_loan_rate_input'];
    $customized_downpayment_ratio_input = $_POST['customized_downpayment_ratio_input'];

    // 插入Financing_Loan_Rate_Setup数据
    $sql = "INSERT INTO Financing_Loan_Rate_Setup (Type, Size, Use_Purpose, Powertrain, Fuel_Type, Model_Year, Financing_Loan_Rate_Setup, Customized_Loan_Rate_Input, Customized_Downpayment_Ratio_Input)
    VALUES ('$type', '$size', '$use_purpose', '$powertrain', '$fuel_type', '$model_year', '$financing_loan_rate_setup', '$customized_loan_rate_input', '$customized_downpayment_ratio_input')";
}elseif ($form_type == 'government') {
        // 从 Government.html 获取额外数据
        $available_for_fast_charging = $_POST['available_for_fast_charging'];
        $government_subsidies_setup = $_POST['government_subsidies_setup'];
        $customized_value_input = $_POST['customized_value_input'];

        // 插入 Government_Subsidies_Setup 表
        $sql = "INSERT INTO Government_Subsidies_Setup (Type, Size, Use_Purpose, Powertrain, Fuel_Type, Model_Year, Available_for_Fast_charging, Government_Subsidies_Setup, Customized_Value_Input)
                VALUES ('$type', '$size', '$use_purpose', '$powertrain', '$fuel_type', '$model_year', '$available_for_fast_charging', '$government_subsidies_setup', '$customized_value_input')";
}elseif ($form_type == 'vehicle') {
        // 从 Vehicle.html 获取额外数据
        $vehicle_purchase_price_setup = $_POST['vehicle_purchase_price_setup'];
        $factor_value = $_POST['factor_value'];

        // 插入 Vehicle_Purchase_Price_Setup 表
        $sql = "INSERT INTO Vehicle_Purchase_Price_Setup (Type, Size, Use_Purpose, Powertrain, Fuel_Type, Model_Year, Vehicle_Purchase_Price_Setup, Factor_Value)
                VALUES ('$type', '$size', '$use_purpose', '$powertrain', '$fuel_type', '$model_year', '$vehicle_purchase_price_setup', '$factor_value')";
}elseif ($form_type == 'condition') {
        // 从 Condition.html 获取额外数据
        $condition_trend = $_POST['condition_trend'];

        // 插入 Condition_Trend 表
        $sql = "INSERT INTO Condition_Trend (Type, Size, Use_Purpose, Powertrain, Fuel_Type, Model_Year, Condition_Trend)
                VALUES ('$type', '$size', '$use_purpose', '$powertrain', '$fuel_type', '$model_year', '$condition_trend')";
    }
// 执行SQL查询
if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>