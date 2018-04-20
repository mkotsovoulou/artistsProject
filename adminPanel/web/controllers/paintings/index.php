<?php

/*
 * This file is part of the CRUD Admin Generator project.
 *
 * Author: Jon Segador <jonseg@gmail.com>
 * Web: http://crud-admin-generator.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../src/app.php';

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/paintings/list', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {  
    $start = 0;
    $vars = $request->request->all();
    $qsStart = (int)$vars["start"];
    $search = $vars["search"];
    $order = $vars["order"];
    $columns = $vars["columns"];
    $qsLength = (int)$vars["length"];    
    
    if($qsStart) {
        $start = $qsStart;
    }    
	
    $index = $start;   
    $rowsPerPage = $qsLength;
       
    $rows = array();
    
    $searchValue = $search['value'];
    $orderValue = $order[0];
    
    $orderClause = "";
    if($orderValue) {
        $orderClause = " ORDER BY ". $columns[(int)$orderValue['column']]['data'] . " " . $orderValue['dir'];
    }
    
    $table_columns = array(
		'id', 
		'title', 
		'paintingtypes_id', 
		'artists_id', 
		'dateadded', 
		'price', 
		'filename', 

    );
    
    $table_columns_type = array(
		'int(11)', 
		'varchar(100)', 
		'int(11)', 
		'int(11)', 
		'timestamp', 
		'decimal(10,2)', 
		'varchar(30)', 

    );    
    
    $whereClause = "";
    
    $i = 0;
    foreach($table_columns as $col){
        
        if ($i == 0) {
           $whereClause = " WHERE";
        }
        
        if ($i > 0) {
            $whereClause =  $whereClause . " OR"; 
        }
        
        $whereClause =  $whereClause . " " . $col . " LIKE '%". $searchValue ."%'";
        
        $i = $i + 1;
    }
    
    $recordsTotal = $app['db']->executeQuery("SELECT * FROM `paintings`" . $whereClause . $orderClause)->rowCount();
    
    $find_sql = "SELECT * FROM `paintings`". $whereClause . $orderClause . " LIMIT ". $index . "," . $rowsPerPage;
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
        for($i = 0; $i < count($table_columns); $i++){

			if($table_columns[$i] == 'paintingtypes_id'){
			    $findexternal_sql = 'SELECT `title` FROM `paintingtypes` WHERE `id` = ?';
			    $findexternal_row = $app['db']->fetchAssoc($findexternal_sql, array($row_sql[$table_columns[$i]]));
			    $rows[$row_key][$table_columns[$i]] = $findexternal_row['title'];
			}
			else if($table_columns[$i] == 'artists_id'){
			    $findexternal_sql = 'SELECT `name` FROM `artists` WHERE `id` = ?';
			    $findexternal_row = $app['db']->fetchAssoc($findexternal_sql, array($row_sql[$table_columns[$i]]));
			    $rows[$row_key][$table_columns[$i]] = $findexternal_row['name'];
			}
			else{
			    $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
			}


        }
    }    
    
    $queryData = new queryData();
    $queryData->start = $start;
    $queryData->recordsTotal = $recordsTotal;
    $queryData->recordsFiltered = $recordsTotal;
    $queryData->data = $rows;
    
    return new Symfony\Component\HttpFoundation\Response(json_encode($queryData), 200);
});




/* Download blob img */
$app->match('/paintings/download', function (Symfony\Component\HttpFoundation\Request $request) use ($app) { 
    
    // menu
    $rowid = $request->get('id');
    $idfldname = $request->get('idfld');
    $fieldname = $request->get('fldname');
    
    if( !$rowid || !$fieldname ) die("Invalid data");
    
    $find_sql = "SELECT " . $fieldname . " FROM " . paintings . " WHERE ".$idfldname." = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($rowid));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('menu_list'));
    }

    header('Content-Description: File Transfer');
    header('Content-Type: image/jpeg');
    header("Content-length: ".strlen( $row_sql[$fieldname] ));
    header('Expires: 0');
    header('Cache-Control: public');
    header('Pragma: public');
    ob_clean();    
    echo $row_sql[$fieldname];
    exit();
   
    
});



$app->match('/paintings', function () use ($app) {
    
	$table_columns = array(
		'id', 
		'title', 
		'paintingtypes_id', 
		'artists_id', 
		'dateadded', 
		'price', 
		'filename', 

    );

    $primary_key = "id";	

    return $app['twig']->render('paintings/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key
    ));
        
})
->bind('paintings_list');



$app->match('/paintings/create', function () use ($app) {
    
    $initial_data = array(
		'title' => '', 
		'paintingtypes_id' => '', 
		'artists_id' => '', 
		'price' => '', 
		'filename' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$options = array();
	$findexternal_sql = 'SELECT `id`, `title` FROM `paintingtypes`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['title'];
	}
	if(count($options) > 0){
	    $form = $form->add('paintingtypes_id', 'choice', array(
	        'required' => true,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('paintingtypes_id', 'text', array('required' => true));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `artists`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('artists_id', 'choice', array(
	        'required' => true,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('artists_id', 'text', array('required' => true));
	}



	$form = $form->add('title', 'text', array('required' => true));
	$form = $form->add('price', 'text', array('required' => true));
	$form = $form->add('filename', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `paintings` (`title`, `paintingtypes_id`, `artists_id`,  `price`,  `filename`) VALUES (?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['title'], $data['paintingtypes_id'], $data['artists_id'],  $data['price'], $data['filename']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'paintings created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('paintings_list'));

        }
    }

    return $app['twig']->render('paintings/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('paintings_create');



$app->match('/paintings/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `paintings` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('paintings_list'));
    }

    
    $initial_data = array(
		'title' => $row_sql['title'], 
		'paintingtypes_id' => $row_sql['paintingtypes_id'], 
		'artists_id' => $row_sql['artists_id'], 
		'dateadded' => $row_sql['dateadded'], 
		'price' => $row_sql['price'], 
		'filename' => $row_sql['filename'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$options = array();
	$findexternal_sql = 'SELECT `id`, `title` FROM `paintingtypes`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['title'];
	}
	if(count($options) > 0){
	    $form = $form->add('paintingtypes_id', 'choice', array(
	        'required' => true,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('paintingtypes_id', 'text', array('required' => true));
	}

	$options = array();
	$findexternal_sql = 'SELECT `id`, `name` FROM `artists`';
	$findexternal_rows = $app['db']->fetchAll($findexternal_sql, array());
	foreach($findexternal_rows as $findexternal_row){
	    $options[$findexternal_row['id']] = $findexternal_row['name'];
	}
	if(count($options) > 0){
	    $form = $form->add('artists_id', 'choice', array(
	        'required' => true,
	        'choices' => $options,
	        'expanded' => false,
	        'constraints' => new Assert\Choice(array_keys($options))
	    ));
	}
	else{
	    $form = $form->add('artists_id', 'text', array('required' => true));
	}


	$form = $form->add('title', 'text', array('required' => true));
	$form = $form->add('dateadded', 'text', array('required' => true));
	$form = $form->add('price', 'text', array('required' => true));
	$form = $form->add('filename', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `paintings` SET `title` = ?, `paintingtypes_id` = ?, `artists_id` = ?, `dateadded` = ?, `price` = ?,  `filename` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['title'], $data['paintingtypes_id'], $data['artists_id'], $data['dateadded'], $data['price'], $data['filename'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'paintings edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('paintings_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('paintings/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('paintings_edit');



$app->match('/paintings/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `paintings` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `paintings` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'paintings deleted!',
            )
        );
    }
    else{
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );  
    }

    return $app->redirect($app['url_generator']->generate('paintings_list'));

})
->bind('paintings_delete');






