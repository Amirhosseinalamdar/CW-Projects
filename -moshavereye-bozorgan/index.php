<?php
	$question = '';
	$msg = "سوال خود را بپرس!";



	$answers = fopen("messages.txt", "r");
	$counter = 0;
	$answersArray = array();
	while (!feof($answers)){
		$answersArray[$counter] = fgets($answers);
		$counter++;
	}
	
	$people = json_decode(file_get_contents('people.json'));							
	$namesArray = array();																


	$counter=0;
	foreach ($people as $key => $value) {
		$counter++;
		$namesArray[$counter] = $key;
	}

	//namesarray va answersarray is set hala user submit mikone
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$en_name =$_POST["person"];
		$question=$_POST["question"];
		$msg=$answersArray[hexdec(hash('crc32b',$en_name." ".$question))%16];
	}
	
	else {
		$en_name=$namesArray[array_rand($namesArray)];
	}


if(! preg_match("/^آیا/iu" , $question) ) {
    $msg = "سوال درستی پرسیده نشده";
}
if(!preg_match("/\?$/i" , $question)  &&  !preg_match("/؟$/u" , $question)){
    $msg = "سوال درستی پرسیده نشده";   
}
	foreach ($people as $key => $value) {
		if ($key==$en_name) {
			$fa_name=$value;
		}
	}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/default.css">
    <title>مشاوره بزرگان</title>
</head>

<body>
    <p id="copyright">تهیه شده برای درس کارگاه کامپیوتر،دانشکده کامییوتر، دانشگاه صنعتی شریف</p>
    <div id="wrapper">
        <div id="title">
            <span id="label"><?php
			if ($question!="")
				{echo "پرسش:";}?></span>
            <span id="question"><?php echo $question ?></span>
        </div>
		
        <div id="container">
            <div id="message">
                <p><?php
                    if ($question!="") {
                        echo $msg;
                    }
					else{
						echo "سوال خود را بپرس!";
					}?></p>
            </div>
            <div id="person">
                <div id="person">
                    <img src="images/people/<?php echo "$en_name.jpg" ?>" />
                    <p id="person-name"><?php echo $fa_name ?></p>
                </div>
            </div>
        </div>
        <div id="new-q">
            <form method="post">
                سوال
                <input type="text" name="question" value="<?php echo $question ?>" maxlength="150" placeholder="..." />
                را از
                <select name="person" value="<?php echo $fa_name ?>" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <?php
                    foreach ($people as $key => $value){
                        if ($en_name == $key){
							echo "<option value=$key selected> $value</option> ";
                        }
						else{
                            echo "<option value=$key > $value</option> ";
                        }
                    }?>
                </select>
                <input type="submit" value="بپرس" />
            </form>
        </div>
    </div>
</body>

</html>