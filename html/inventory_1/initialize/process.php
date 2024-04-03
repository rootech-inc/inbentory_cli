<?php

require 'db.php';
session_start();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $stage = $_SESSION['stage'];

    $iniFilePath = '../config.ini';



    if($stage === 'DB_SETUP'){
        // setup db
        $db_host = htmlentities($_POST['db_host']);
        $db_name = htmlentities($_POST['db_name']);
        $db_user = htmlentities($_POST['db_user']);
        $db_password = htmlentities($_POST['db_password']);

        // text db connection
        try {
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_password);
            $data = array(
                'HOST' => "$db_host",
                'PASSWORD' => "$db_password",
                'USER' => "$db_user",
                'NAME' => "$db_name",
                'PORT' => "1"
            );
            // Check if the INI file exists
            if (file_exists($iniFilePath)) {
                // Read the existing INI file
                $iniData = parse_ini_file($iniFilePath, true);

                // Check if the COMPANY_SETUP section exists
                if (isset($iniData['DB_NETWORK'])) {
                    // Append the new data to the existing section
                    $iniData['DB_NETWORK'] = array_merge($iniData['DB_NETWORK'], $data);
                } else {
                    // Create the COMPANY_SETUP section and add the data
                    $iniData['DB_NETWORK'] = $data;
                }

                // Convert the array back to INI format
                $newIniContent = '';
                foreach ($iniData as $section => $values) {
                    $newIniContent .= "[$section]\n";
                    foreach ($values as $key => $value) {
                        $newIniContent .= "$key = \"$value\"\n";
                    }
                    $newIniContent .= "\n";
                }

                // Write the new content to the INI file
                file_put_contents($iniFilePath, $newIniContent);

            }

            if(isset($_POST['db_ini'])){
                $db_ini = $_POST['db_ini'];
                if($db_ini === 'on'){
                    // initialize db
                    $user_query = "
                    truncate table user_group;
                    insert into user_group (descr,remarks) values ('System Administrators','Adminsirative permissions'),('Clerks','Sales Personnel');
                    DELETE FROM clerk where id > 0;
                    insert into clerk (id,clerk_code,clerk_key,clerk_name,user_grp,pin,token) values 
                    (1,'411','17d63b1625c816c22647a73e1482372b','Admin',1,1444,1444);
                    DELETE FROM tax_master where id > 0;
                    INSERT INTO tax_master (id,description,rate,owner,active,attr) values (1,'Not Taxable',0.00,1,1,'NON');
                    DELETE FROM packaging where id > 0;
                    INSERT INTO packaging (id,`desc`) values (1,'PCS'),(2,'CTN'),(3,'LIT'),(4,'KG');
                    DELETE FROM stock_type where id > 0;
                    INSERT INTO stock_type (id,description) values (1,'Regular'),(2,'Non-Stock'),(3,'Discontinued');
                    TRUNCATE table system_buttons;
                    INSERT INTO system_buttons (button_id, module, sub_module, sub_sub_module, descr, elem_id, elem_name, status, target_id) VALUES 
                    (1, 'inventory', 'products', 'product_details', 'PRICES', 'inv_prod_prices', 'inv_prod_prices', 1, 'price'), 
                    (2, 'inventory', 'products', 'product_details', 'STOCK', 'inv_prod_stock', 'inv_prod_stock', 1, 'stock'),
                    (3, 'inventory', 'products', 'product_details', 'PACKING', 'inv_prod_packing_tab', 'inv_prod_packing_tab', 1, 'packing_tab'),
                    (4, 'inventory', 'products', 'product_details', 'BARCODE', 'inv_prod_more_barcode', 'inv_prod_more_barcode', 1, 'more_barcode'),
                    (5, 'inventory', 'products', 'product_details', 'SUPPLIER', 'inv_prod_more_supplier', 'inv_prod_more_supplier', 1, 'more_supplier');
                    
                    DELETE FROM bill_trans;
                    DELETE FROM bill_header;
                    DELETE FROM bill_tax_tran;
                    DELETE FROM shifts;
                    DELETE FROM sales_hd;
                    DELETE FROM sales_tran;
                    DELETE FROM eod_serial;
                    DELETE FROM zserial;
                    
                    TRUNCATE table bill_trans;
                    TRUNCATE table  bill_header;
                    TRUNCATE table  bill_tax_tran;
                    TRUNCATE table  shifts;
                    TRUNCATE table  sales_hd;
                    TRUNCATE table  sales_tran;
                    TRUNCATE table  eod_serial;
                    TRUNCATE table  zserial;
                ";

                    //$pdo->prepare($user_query);
                    $pdo->exec($user_query);

                    $key = md5(411);
                    // create admin account
                    $Tr = "DELETE FROM venta.clerk";
                    $admin_q = "INSERT INTO venta.clerk (clerk_code, clerk_key, date_created, clerk_name, user_grp, status, pin, token)
                    VALUES (411, '$key', DEFAULT, 'Admin', 1, DEFAULT, 1444, 1444);
                    ";

                    $t = $pdo->prepare($Tr);
                    $t->execute();
                    $ex = $pdo->prepare($admin_q);
                    $ex->execute();
                }
            }
            $_SESSION['stage'] = strtoupper("system_config");
        } catch (PDOException $e){
            $error = $e->getMessage();
            header("Location:".$_SERVER['HTTP_REFERER'].'?error='.$error);
            exit();


        }
        header("Location:".$_SERVER['HTTP_REFERER']);
        exit();
    }

    elseif ($stage === 'SYSTEM_CONFIG'){
        $mech_no = htmlentities($_POST['mech_no']);
        $mac_addr = htmlentities($_POST['mac_addr']);
        $name = htmlentities($_POST['name']);
        $debug = htmlentities($_POST['debug']);

        // print data
        $print_type = htmlentities($_POST['print_type']);
        $print_name = htmlentities($_POST['print_name']);
        $print_status = htmlentities($_POST['print_status']);

        $print_data = array(
            'NAME'=>$print_name,
            'PRINT_TYPE'=>$print_type,
            'BILL_PRINT'=>$print_status
        );


        $data = array(
            "DEBUG"=>$debug,
            'MACH_NO'=>$mech_no,
            'MAC_ADDRESS'=>$mac_addr,
            "NAME"=>$name
        );

        if (file_exists($iniFilePath)) {
            // Read the existing INI file
            $iniData = parse_ini_file($iniFilePath, true);

            // Check if the COMPANY_SETUP section exists
            if (isset($iniData['system_config'])) {
                // Append the new data to the existing section
                $iniData['system_config'] = array_merge($iniData['system_config'], $data);
            } else {
                // Create the COMPANY_SETUP section and add the data
                $iniData['system_config'] = $data;
            }

            // Convert the array back to INI format
            $newIniContent = '';
            foreach ($iniData as $section => $values) {
                $newIniContent .= "[$section]\n";
                foreach ($values as $key => $value) {
                    $newIniContent .= "$key = \"$value\"\n";
                }
                $newIniContent .= "\n";
            }

            // Write the new content to the INI file
            file_put_contents($iniFilePath, $newIniContent);

            if (isset($iniData['PRINTER'])) {
                // Append the new data to the existing section
                $iniData['PRINTER'] = array_merge($iniData['PRINTER'], $print_data);
            } else {
                // Create the COMPANY_SETUP section and add the data
                $iniData['PRINTER'] = $print_data;
            }

            // Convert the array back to INI format
            $newIniContent = '';
            foreach ($iniData as $section => $values) {
                $newIniContent .= "[$section]\n";
                foreach ($values as $key => $value) {
                    $newIniContent .= "$key = \"$value\"\n";
                }
                $newIniContent .= "\n";
            }

            // Write the new content to the INI file
            file_put_contents($iniFilePath, $newIniContent);

        }
        $_SESSION['stage'] = strtoupper("api_configuration");




    }

    elseif ($stage === 'API_CONFIGURATION'){
        $lty_status = htmlentities($_POST['lty_status']);
        $loy_token = htmlentities($_POST['loy_token']);
        $loy_url = htmlentities($_POST['loy_url']);
        $evat_status = htmlentities($_POST['evat_status']);
        $evat_end_point = htmlentities($_POST['evat_end_point']);

        $ldata = array(
            'ACTIVE'=>$lty_status,
            "API_TOKEN"=>$loy_token,
            "BASE_URL"=>$loy_url
        );

        $edata = array(
            "ACTIVE"=>$evat_status,
            'BASE_URL'=>$evat_end_point,
        );

        if (file_exists($iniFilePath)) {
            // Read the existing INI file
            $iniData = parse_ini_file($iniFilePath, true);

            // Check if the COMPANY_SETUP section exists
            if (isset($iniData['EVAT'])) {
                // Append the new data to the existing section
                $iniData['EVAT'] = array_merge($iniData['EVAT'], $edata);
            } else {
                // Create the COMPANY_SETUP section and add the data
                $iniData['EVAT'] = $edata;
            }

            // Convert the array back to INI format
            $newIniContent = '';
            foreach ($iniData as $section => $values) {
                $newIniContent .= "[$section]\n";
                foreach ($values as $key => $value) {
                    $newIniContent .= "$key = \"$value\"\n";
                }
                $newIniContent .= "\n";
            }

            // Write the new content to the INI file
            file_put_contents($iniFilePath, $newIniContent);

            // Read the existing INI file
            $iniData = parse_ini_file($iniFilePath, true);

            // Check if the COMPANY_SETUP section exists
            if (isset($iniData['LOYALTY'])) {
                // Append the new data to the existing section
                $iniData['LOYALTY'] = array_merge($iniData['LOYALTY'], $ldata);
            } else {
                // Create the COMPANY_SETUP section and add the data
                $iniData['LOYALTY'] = $ldata;
            }

            // Convert the array back to INI format
            $newIniContent = '';
            foreach ($iniData as $section => $values) {
                $newIniContent .= "[$section]\n";
                foreach ($values as $key => $value) {
                    $newIniContent .= "$key = \"$value\"\n";
                }
                $newIniContent .= "\n";
            }

            // Write the new content to the INI file
            file_put_contents($iniFilePath, $newIniContent);

        }

        $_SESSION['stage'] = strtoupper("company_setup");

    }

    elseif ($stage === 'COMPANY_SETUP'){
        $c_name = htmlspecialchars($_POST["name"]);
        $code = htmlspecialchars($_POST["code"]);
        $tax_code = htmlspecialchars($_POST["tax_code"]);
        $country = htmlspecialchars($_POST["country"]);
        $city = htmlspecialchars($_POST["city"]);
        $street = htmlspecialchars($_POST["street"]);
        $box = htmlspecialchars($_POST["box"]);
        $phone = htmlspecialchars($_POST["phone"]);
        $email = htmlspecialchars($_POST["email"]);
        $footer = htmlspecialchars($_POST["footer"]);
        $currency = 1;

        $logo  = $_FILES["logo"];
        $fileName = $_FILES["logo"]["name"];
        $fileTmpName = $_FILES["logo"]["tmp_name"];
        $uploadDirectory = '../assets/logo/';
        move_uploaded_file($fileTmpName, $uploadDirectory . $fileName);

        $iniFilePath = '../config.ini';


        $data = array(
            'NAME' => "$c_name",
            'CURRENCY' => 1,
            'BOX' => "$box",
            'Street' => "$street",
            'COUNTRY' => "$country",
            'CITY' => "$city",
            'PHONE' => "$phone",
            'EMAIL' => "$email",
            'TAX_CODE' => "$tax_code",
            'FOOTER' => "$footer",
            'CODE' => '001',
            'LOGO'=> "$fileName"
        );

        // Check if the INI file exists
        if (file_exists($iniFilePath)) {
            // Read the existing INI file
            $iniData = parse_ini_file($iniFilePath, true);

            // Check if the COMPANY_SETUP section exists
            if (isset($iniData['COMPANY_SETUP'])) {
                // Append the new data to the existing section
                $iniData['COMPANY_SETUP'] = array_merge($iniData['COMPANY_SETUP'], $data);
            } else {
                // Create the COMPANY_SETUP section and add the data
                $iniData['COMPANY_SETUP'] = $data;
            }

            // Convert the array back to INI format
            $newIniContent = '';
            foreach ($iniData as $section => $values) {
                $newIniContent .= "[$section]\n";
                foreach ($values as $key => $value) {
                    $newIniContent .= "$key = \"$value\"\n";
                }
                $newIniContent .= "\n";
            }

            // Write the new content to the INI file
            file_put_contents($iniFilePath, $newIniContent);

        }

        $_SESSION['stage'] = strtoupper("setup_complete");
    }

    elseif($stage === 'initialize'){


// Set PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // get data
        $c_name = htmlspecialchars($_POST["name"]);
        $code = htmlspecialchars($_POST["code"]);
        $tax_code = htmlspecialchars($_POST["tax_code"]);
        $country = htmlspecialchars($_POST["country"]);
        $city = htmlspecialchars($_POST["city"]);
        $street = htmlspecialchars($_POST["street"]);
        $box = htmlspecialchars($_POST["box"]);
        $phone = htmlspecialchars($_POST["phone"]);
        $email = htmlspecialchars($_POST["email"]);
        $footer = htmlspecialchars($_POST["footer"]);
        $currency = 1;

        $logo  = $_FILES["logo"];
        $fileName = $_FILES["logo"]["name"];
        $fileTmpName = $_FILES["logo"]["tmp_name"];
        $uploadDirectory = '../assets/logo/';
        move_uploaded_file($fileTmpName, $uploadDirectory . $fileName);

        $iniFilePath = '../config.ini';


        $data = array(
            'NAME' => "$c_name",
            'CURRENCY' => 1,
            'BOX' => "$box",
            'Street' => "$street",
            'COUNTRY' => "$country",
            'CITY' => "$city",
            'PHONE' => "$phone",
            'EMAIL' => "$email",
            'TAX_CODE' => "$tax_code",
            'FOOTER' => "$footer",
            'CODE' => '001',
            'LOGO'=> "$fileName"
        );

        // Check if the INI file exists
        if (file_exists($iniFilePath)) {
            // Read the existing INI file
            $iniData = parse_ini_file($iniFilePath, true);

            // Check if the COMPANY_SETUP section exists
            if (isset($iniData['COMPANY_SETUP'])) {
                // Append the new data to the existing section
                $iniData['COMPANY_SETUP'] = array_merge($iniData['COMPANY_SETUP'], $data);
            } else {
                // Create the COMPANY_SETUP section and add the data
                $iniData['COMPANY_SETUP'] = $data;
            }

            // Convert the array back to INI format
            $newIniContent = '';
            foreach ($iniData as $section => $values) {
                $newIniContent .= "[$section]\n";
                foreach ($values as $key => $value) {
                    $newIniContent .= "$key = \"$value\"\n";
                }
                $newIniContent .= "\n";
            }

            // Write the new content to the INI file
            file_put_contents($iniFilePath, $newIniContent);

        }




        // add user groups
        $user_query = "
            truncate table user_group;
            insert into user_group (descr,remarks) values ('System Administrators','Adminsirative permissions'),('Clerks','Sales Personnel');
            DELETE FROM clerk where id > 0;
            insert into clerk (id,clerk_code,clerk_key,clerk_name,user_grp,pin,token) values 
            (1,'411','17d63b1625c816c22647a73e1482372b','Admin',1,1444,1444);
            DELETE FROM tax_master where id > 0;
            INSERT INTO tax_master (id,description,rate,owner,active,attr) values (1,'Not Taxable',0.00,1,1,'NON');
            DELETE FROM packaging where id > 0;
            INSERT INTO packaging (id,`desc`) values (1,'PCS'),(2,'CTN'),(3,'LIT'),(4,'KG');
            DELETE FROM stock_type where id > 0;
            INSERT INTO stock_type (id,description) values (1,'Regular'),(2,'Non-Stock'),(3,'Discontinued');
            TRUNCATE table system_buttons;
            INSERT INTO system_buttons (button_id, module, sub_module, sub_sub_module, descr, elem_id, elem_name, status, target_id) VALUES 
            (1, 'inventory', 'products', 'product_details', 'PRICES', 'inv_prod_prices', 'inv_prod_prices', 1, 'price'), 
            (2, 'inventory', 'products', 'product_details', 'STOCK', 'inv_prod_stock', 'inv_prod_stock', 1, 'stock'),
            (3, 'inventory', 'products', 'product_details', 'PACKING', 'inv_prod_packing_tab', 'inv_prod_packing_tab', 1, 'packing_tab'),
            (4, 'inventory', 'products', 'product_details', 'BARCODE', 'inv_prod_more_barcode', 'inv_prod_more_barcode', 1, 'more_barcode'),
            (5, 'inventory', 'products', 'product_details', 'SUPPLIER', 'inv_prod_more_supplier', 'inv_prod_more_supplier', 1, 'more_supplier');
            
            DELETE FROM bill_trans;
            DELETE FROM bill_header;
            DELETE FROM bill_tax_tran;
            DELETE FROM shifts;
            DELETE FROM sales_hd;
            DELETE FROM sales_tran;
            DELETE FROM eod_serial;
            DELETE FROM zserial;
            
            TRUNCATE table bill_trans;
            TRUNCATE table  bill_header;
            TRUNCATE table  bill_tax_tran;
            TRUNCATE table  shifts;
            TRUNCATE table  sales_hd;
            TRUNCATE table  sales_tran;
            TRUNCATE table  eod_serial;
            TRUNCATE table  zserial;
        ";

        //$pdo->prepare($user_query);
        $pdo->exec($user_query);

        $key = md5(411);
        // create admin account
        $Tr = "DELETE FROM venta.clerk";
        $admin_q = "INSERT INTO venta.clerk (clerk_code, clerk_key, date_created, clerk_name, user_grp, status, pin, token)
        VALUES (411, '$key', DEFAULT, 'Admin', 1, DEFAULT, 1444, 1444);
        ";

        $t = $pdo->prepare($Tr);
        $t->execute();
        $ex = $pdo->prepare($admin_q);
        $ex->execute();


    }
}


header("Location:".$_SERVER['HTTP_REFERER']);