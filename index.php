<?php 

$errors = array();
$display_thanks=false;

error_reporting(-1);
ini_set('display_errors','on');

include 'includes/filter-wrapper.php';


$possible_subjects = array(
		'Transformers'
		,'Star Wars'
		,'Lego'
);

$possible_priorities = array(
      'eng' =>'English'
	  ,'french' => 'French'
	  ,'span' => 'Spanish'

);

$name =  filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email',FILTER_SANITIZE_EMAIL);
$notes = filter_input(INPUT_POST, 'notes',FILTER_SANITIZE_STRING);
$picknum = filter_input(INPUT_POST, 'picknum',FILTER_SANITIZE_NUMBER_INT);
$subject = filter_input(INPUT_POST, 'subject',FILTER_SANITIZE_STRING);
$priority = filter_input(INPUT_POST, 'priority',FILTER_SANITIZE_STRING);
$terms = filter_input(INPUT_POST,'terms',FILTER_DEFAULT);
$password = filter_input(INPUT_POST, 'password',FILTER_SANITIZE_STRING);
//var_dump($name); 
// this is like error validator...like trace command.
//filter_input(input_type, variable, filter, options)

if ($_SERVER['REQUEST_METHOD'] =='POST') { //check to see if the form has been submitted before validating
if(empty($name)) {
	$errors['name'] = true;
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
	$errors['email']=true;
}

if(mb_strlen($notes) < 25){
	$errors['notes']= true;
	}
	
if ($picknum < 1 || $picknum > 10){
	$errors['picknum']=true;
    }
	
if(!in_array($subject,$possible_subjects)) {
	$errors['subject']= true;
}

if(!array_key_exists($priority,$possible_priorities)) {
	$errors['priority']= true;
}

/*if our user checked the check box it will be sent inside the $_post variable.
if our user didnt check the box,it wont be in the $_post variable

if(!isset($_POST['terms'])){
	$errors['terms']=true;
}*/

if(empty($terms)){
	$errors['terms']=true;
}

/*if the errors in array is empty,all the user submitted content is valid.
  if there is anything inside $errors,something isnt valid*/
if(empty($errors)){
	$display_thanks=true;
	$email_message= 'Name:' .$name ."\r\n";  //"\r\n" is a new line in an email.
	$email_message .= 'Email :' .$email ."\r\n";
	$email_message .= "Message:\r\n" . $notes;
	
	$headers = 'From:' .$name. '<' .$email .'>' ."\r\n";
	//$headers = 'From:Priyanka Gite <gite0002@algonquinlive.com>';
	
    
	mail('gite0002@algonquinlive.com', $subject,$email_message,$headers);
	//mail($email,'thanks for registrating')
}
if(mb_strlen($notes) < 9){
	$errors['password']= true;
	}

}


?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>contact form</title>
<link href="css/general.css" rel="stylesheet">
</head>

<body>
    <?php if($display_thanks):?>
      <strong>Thanks!</strong>
    <?php else: ?>
	<form method="post" action="index.php">
    <div>
       <label for="name">Name<?php if(isset($errors['name'])) :?><strong> is required</strong><?php endif; ?></label>
       <input id="name" name="name" value="<?php echo $name; ?>"  required >
    </div>
    
    <div>
       <label for="email">E-mail Adress<?php if(isset($errors['email'])) :?><strong> is required</strong><?php endif; ?></label>
       <input type="email" id="email" name="email" value="<?php echo $email; ?>"  required >
    </div>
    
    <div>
       <label for="subject">Subject<?php if(isset($errors['subject'])) :?><strong> is required</strong><?php endif; ?></label>
       <select id="subject" name="subject">
       <?php foreach ($possible_subjects as $current_subject): ?>
         <option <?php if($current_subject == $subject){echo 'selected'; }?>> <?php echo $current_subject; ?> </option>
         <?php endforeach; ?>
      
       </select>
       </div>
    
    <div>
        <label for ="notes">Notes<?php if(isset($errors['notes'])) :?><strong> is required</strong><?php endif; ?></label>
        <textarea id = "notes" name="notes"required > <?php echo $notes; ?> </textarea>
    </div>
    
     <div>
        <label for ="password">Password<?php if(isset($errors['password'])) :?><strong> is required</strong><?php endif; ?></label>
        <input type="password" id = "password" name="password"required > <?php echo $password; ?> </textarea>
    </div>
    
    <fieldset>
        <legend>Select A Language</legend>
        <?php if(isset($errors['priority'])) :?><strong> Select A Language</strong><?php endif; ?>
        <?php foreach($possible_priorities as $key => $value): ?>
        <input type="radio" id="<?php echo $key; ?>" name="priority" value="<?php echo $key;?>" <?php if($key == $priority) {echo ' checked'; } ?> >
        <label for="<?php echo $key; ?>"> <?php echo $value; ?></label>
        <?php endforeach; ?>
    </fieldset>
    <div>
        <label for="picknum">Pick a no between 1 and 10 <?php if(isset($errors['picknum'])) :?><strong> is required</strong><?php endif; ?></label>
        <input type="number" id="picknum" name="picknum" value="<?php echo $picknum; ?>"  required >
    </div>
    
    <div>
        <input type="checkbox" id="terms" name="terms" <?php if(!empty($terms)) {echo 'checked';} ?>>
        <label for ="terms">Accept terms?</label>
        <?php if(isset($errors['terms'])):?><strong>You must comply</strong><?php endif; ?>
    </div>
    
    <div>
        <button type="submit">Send Message</button>
    </div>
    </form>
    
<?php endif; ?>        
       
</body>
</html>