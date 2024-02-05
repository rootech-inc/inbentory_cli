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
$api_resp = (new \API\ApiResponse());
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

        if($db->row_count('customers',"`cust_no` = '$cust_no'") === 1){
            $customer = $db->get_rows('customers',"`cust_no` = '$cust_no'",'json');
            (new \API\ApiResponse())->success(json_decode($customer));
        } else {
            (new \API\ApiResponse())->error("CANNOT FIND CUSTOMER");
        }

    }

    elseif ($module === 'take_eod') {
        (new \API\ApiResponse())->success(json_decode("OKAY"));
    }


}


function handlePostRequest($module, $data,$crud)
{
    $db= (new \db_handeer\db_handler());
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

                $custs = $db->row_count('customers',"`customer_id` > 0") + 1;
                $cust_no = "CO" . ($custs + 1);

                // Prepare the database query
                $db_conn = $db->db_connect();
                $query = "INSERT INTO customers (first_name, last_name, email, phone_number, address, city, postal_code, country,cust_no) 
              VALUES (:first_name, :last_name, :email, :phone, :address, :city, :postal_code, :country,:cust_no)";
                $statement = $db_conn->prepare($query);

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
        }
        elseif ($crud === 'read')
        {
            $cust_no = $data['cust_no'];

            if($db->row_count('customers',"`cust_no` = '$cust_no'") === 1){
                $customer = $db->get_rows('customers',"`cust_no` = '$cust_no'",'json');

                (new \API\ApiResponse())->success(json_decode($customer));
            } else {
                (new \API\ApiResponse())->error("CANNOT FIND CUSTOMER");
            }

        }
        elseif ($crud === 'statement'){
            $db = (new \db_handeer\db_handler());
            $cust_no = $data['cust_no'];
            $customer = $db->fetch_rows("select CONCAT(first_name,' ',last_name) as 'name',email,phone_number,CONCAT(country,', ',city,', ',address) as 'address',total_transactions as 'balance',customer_id from customers where cust_no = '$cust_no'");
            $customer_id = $customer['customer_id'];
            $pdf = (new FPDF('P','mm','A4'));
            $pdf->AddPage();
            $pdf->SetFont('Arial','B','20');
            $pdf->Cell(190,10,"CUSTOMER FINANCIAL STATEMENT",1,1,'C','','');
            $pdf->Ln(10);
            # name
            $pdf->SetFont('Arial','B','10');
            $pdf->Cell(20,6,"Name : ",'',0,'L');
            $pdf->SetFont('Arial','','10');
            $pdf->Cell(75,6,$customer['name'],'',0,'L');
            # name

            #balance
            $pdf->SetFont('Arial','B','10');
            $pdf->Cell(75,6,"Balance : ",'',0,'R');
            $pdf->SetFont('Arial','','10');
            $pdf->Cell(20,6,'GHS '.number_format($customer['balance'],2),'',1,'R');
            #balance

            #address
            $pdf->SetFont('Arial','B','10');
            $pdf->Cell(20,6,"Address : ",'',0,'L');
            $pdf->SetFont('Arial','','10');
            $pdf->Cell(170,6,$customer['address'],'',1,'L');
            #address

            #email
            $pdf->SetFont('Arial','B','10');
            $pdf->Cell(20,6,"Email : ",'',0,'L');
            $pdf->SetFont('Arial','','10');
            $pdf->Cell(170,6,$customer['email'],'',1,'L');
            #email

            #phone
            $pdf->SetFont('Arial','B','10');
            $pdf->Cell(20,5,"Phone : ",'',0,'L');
            $pdf->SetFont('Arial','','10');
            $pdf->Cell(170,5,$customer['phone_number'],'',1,'L');
            $pdf->Ln(10);
            #phone

            $sql = "SELECT transaction_date,total_amount, payment_method,items_purchased,transaction_notes FROM customers_trans where customer_id = '$customer_id'";
            $stmt = $db->db_connect()->prepare($sql);
            $stmt->execute();
            # table header
            if($stmt->rowCount() > 0){
                $pdf->SetFont('Arial','B','10');
                # get transactions
                $pdf->Cell(38,5,"DATE",1,0,"L");
                $pdf->Cell(38,5,"AMOUNT",1,0,"L");
                $pdf->Cell(38,5,"METHOD",1,0,"L");
                $pdf->Cell(38,5,"REFERENCE",1,0,"L");
                $pdf->Cell(38,5,"NOTE",1,1,"L");
                $pdf->SetFont('Arial','','8');
                while($transaction = $stmt->fetch(PDO::FETCH_ASSOC)){
                    $pdf->Cell(38,5,$transaction['transaction_date'],1,0,"L");
                    $pdf->Cell(38,5,$transaction['total_amount'],1,0,"L");
                    $pdf->Cell(38,5,$transaction['payment_method'],1,0,"L");
                    $pdf->Cell(38,5,$transaction['items_purchased'],1,0,"L");
                    $pdf->Cell(38,5,$transaction['transaction_notes'],1,1,"L");
                }
            }

            $pdf->Output('F','test.pdf');
            (new \API\ApiResponse())->success("PRINT SUCCESSFUL");
        }

        elseif ($crud === 'load'){
            $cust_code = $data['code'];
            $db = (new \db_handeer\db_handler());
            if($db->row_count('customer',"`cust_no` = '$cust_code'") === 1){
                $customer = $db->get_rows('customer',"`cust_no` = '$cust_code'");
                # add customer bill transaction
            } else {
                (new API\ApiResponse())->error("CUSTOMER NOT FOUND");
            }
        }

        else{
            (new API\ApiResponse())->error("NOT CONFIGURED MODULE");
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

    elseif ($module === 'clrk_auth'){
        $token = $data['token'];
        $db = (new \db_handeer\db_handler());

        //encrype string
        $enc_string = md5($token);
        if($db->row_count('clerk',"`token` = '$enc_string'") === 1){
            // login
            (new API\ApiResponse())->success("Login Successful");
        } else {
            (new \API\ApiResponse())->error("Invalid Token");
        }

    }

}
