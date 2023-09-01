<?php

// Include any necessary dependencies and configuration files
include  "../backend/includes/core.php";
include "ApiResponse.php";

$response = new \API\ApiResponse();



// Define the HTTP request method
$request_method = $_SERVER['REQUEST_METHOD'];

// Extract the module and data from the API body
$api_body = json_decode(file_get_contents('php://input'), true);

$module = $api_body['module'];
$data = $api_body['data'];
$crud = $api_body['crud'];

// Perform input validation
$valid_data = validateData($data);

// Execute the appropriate action based on the request method

$response = array("code"=>0,"message"=>"HELLO WORLD");

switch ($request_method) {
    case 'GET':
        // Handle GET request (VIEW)
        handleViewRequest($module,$valid_data);
        break;
    case "POST":
        handlePostRequest($module,$valid_data,$crud);
        break;
    default:
        // Invalid request method
        http_response_code(405); // Method Not Allowed

        break;
}

// return function


// Validation function
function validateData($data)
{
    // Perform data validation here
    // ...
    // Return the validated data
    return $data;
}

// Handler functions for each request method

function handleViewRequest($module,$data)
{
    // Handle VIEW (GET) request logic here

    global $response;
    if($module === 'bill_summary'){
        $billRef = $data['billRef'];
        // get bill summary
        $bill = (new \billing\Billing())->billSummaryV2('001230129291',1);
        echo $response->success($bill);
    }

    elseif($module === 'customer'){
        $cust_no = $data['cust_no'];

        if((new db_handeer\db_handler())->row_count('customers',"`cust_no` = '$cust_no'") === 1){
            $customer = (new db_handeer\db_handler())->get_rows('customers',"`cust_no` = '$cust_no'",'json');
            (new \API\ApiResponse())->success(json_decode($customer));
        } else {
            (new \API\ApiResponse())->error("CANNOT FIND CUSTOMER");
        }

    }


}


function handlePostRequest($module, $data,$crud)
{
    if($module === 'customer'){
        // create customer
        if($crud === 'write'){
            try {
                // Retrieve data from the request
                $first_name = $data['first_name'];
                $last_name = $data['last_name'];
                $email = $data['email'];
                $phone = $data['phone'];
                $city = $data['city'];
                $postal_code = $data['postal_code'];
                $country = $data['country'];
                $address = $data['address'];

                $cust_no = (new db_handeer\db_handler())->row_count('customers',"`customer_id` > 0") + 1;
                $cust_no += 9990000000;

                // Prepare the database query
                $db = (new db_handeer\db_handler())->db_connect();
                $query = "INSERT INTO customers (first_name, last_name, email, phone_number, address, city, postal_code, country,cust_no) 
              VALUES (:first_name, :last_name, :email, :phone, :address, :city, :postal_code, :country,:cust_no)";
                $statement = $db->prepare($query);

                // Bind parameters and execute the query
                $statement->bindParam(':first_name', $first_name);
                $statement->bindParam(':last_name', $last_name);
                $statement->bindParam(':email', $email);
                $statement->bindParam(':phone', $phone);
                $statement->bindParam(':address', $address);
                $statement->bindParam(':city', $city);
                $statement->bindParam(':postal_code', $postal_code);
                $statement->bindParam(':country', $country);
                $statement->bindParam(':cust_no', $cust_no);

                // Execute the query
                $result = $statement->execute();

                if ($result) {
                    (new \API\ApiResponse())->success("Customer Created");
                } else {
                    (new \API\ApiResponse())->success("Could Not Save Customer");
                }
            } catch (PDOException $e) {
                (new \API\ApiResponse())->success($e->getMessage());
            }
        } elseif ($crud === 'read'){
            $cust_no = $data['cust_no'];

            if((new db_handeer\db_handler())->row_count('customers',"`cust_no` = '$cust_no'") === 1){
                $customer = (new db_handeer\db_handler())->get_rows('customers',"`cust_no` = '$cust_no'",'json');

                (new \API\ApiResponse())->success(json_decode($customer));
            } else {
                (new \API\ApiResponse())->error("CANNOT FIND CUSTOMER");
            }

        }
    }

    elseif ($module === 'dis_en_customer'){
        $cust_no = $data['cust_code'];
        $task = $data['task'];
        $status = 0;
        if($task === 'enable'){
            $status = 1;
        }

        (new \db_handeer\db_handler())->exe("UPDATE customers SET status = $status where cust_no = '$cust_no'");

        (new \API\ApiResponse())->success("Customer Modified");

    }

    elseif ($module === 'customer_in_transit'){
        $billRef = billRef;

        // check if there is a bill in transit for bill ref


    }

}
