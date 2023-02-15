
<?php
	//Create Session
	if (!isset($_SESSION)) {
		session_start();
	}

    //Create variables to hold form data and errors
    $nameErr = $emailErr = "";
    $name = $email = $comment = "";
    $formErr = false;

    //Validate form when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (empty(trim($_POST["name"]))) {
            $nameErr = "Name is required";
            $formErr = true;
        } else {
            $name = cleanInput($_POST["name"]);
            //Use REGEX to accept only letters and white spaces
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                $nameErr = "Only letters and standard spaces allowed";
                $formErr = true;
            }
        }

        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
            $formErr = true;
        } else {
            $email = cleanInput($_POST["email"]);
            // Check if e-mail address is formatted correctly
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Please enter a valid email address";
                $formErr = true;
            }
        }


        $comment = cleanInput($_POST["comments"]);
    }

    //Clean and sanitize form inputs
    function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * If no form errors occur, 
     * send the data to the database
     */
    if (($_SERVER["REQUEST_METHOD"] == "POST") && (!($formErr))){
		//Create Connection Variables
		$hostname = "mysql.bobbiteel.slccwebdev.com";
		$username = "bobbi";
		$password = "pistachio4sure";
		$databasename = "bobbi_portfolio";

        try {
            //Create new PDO Object with connection parameters
            $conn = new PDO("mysql:host=$hostname;dbname=$databasename",$username, $password);

            //Set PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            $sql = "INSERT INTO bobbiContacts (name, email, comments) VALUES (:name, :email, :comment);";

            //Variable containing SQL command
            $stmt = $conn->prepare($sql);

            //Bind parameters
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);

            //Execute SQL statement on server
            $stmt->execute();

            //Build success message to display
            $_SESSION['message'] = '<p class="font-weight-bold">Thank you for your submission!</p><p class="font-weight-light" >Your request has been sent.</p>';

            $_SESSION['complete'] = true;

            //Redirect
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;

        } catch (PDOException $error) {

            //Build error message to display
            $_SESSION['message'] =  "<p>We apologize, the form was not submitted successfully. Please try again later.</p>";
            // Uncomment code below to troubleshoot issues
            // echo '<script>console.log("DB Error: ' . addslashes($error->getMessage()) . '")</script>';
            $_SESSION['complete'] = true;
            //Redirect
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

        $conn = null;
    } 
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> 
    <scrip src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="main.css"/>
    <link rel="icon" type="image/x-icon" href="/PHP/assets/favicon.ico">
    <title>BK TEEL</title> 
  </head>

<body>
    <div style="overflow:auto">
        <div id="Home-Page-Top"></div>
        <nav id="navbar">
            <a href="#work">Work</a>
            <a href="about.php">About</a>
            <div class="top"><a href="#top">B.</a></div>
            <a href="Resume.pdf" target="_blank">Resume</a>
            <a href="#contact">Contact</a>
        </nav>

        <div class="hero">
            <h1>Hi, I'm Bobbi.<br></h1> 
            <p>Full-Stack Web Developer skilled in client<br> 
                and server side programming. I'm passionate<br> 
                about every detail, from concept and ideation<br>
                to execution of the final product.<br></p>
                
            <div class="profile"><img src="/PHP/assets/profile.png"></div>
        </div>

        <div class="container"> 
            <div class="container-1">  
                <h2 id="work"><b>RECENT WORK</b></h2>
                <div class="container-1-flex">
                    <div class="container-1-text">
                        <h3><b>Marty's Dark Matter</b></h3>
                        <p>A website redesign for an author transitioning from a blog format to a more modern design.</p>
                        <div class="container-flex-tab">
                            <h5 class="font5">Wordpress</h5>
                            <h5 class="font5">Frontend</h5>
                        </div>
                        <a href="martys.php">
                            <div class="button-1">Learn More</div>
                        </a>
                    </div>
                    <div class="martys"><a href="https://martysdarkmatter.com" target="_blank"><img alt="author website" src="/PHP/assets/martys.png">
                    </a></div>
                </div>
            </div>
        </div>

        <section>     
        <h2 id="contact"><b>CONTACT ME</b></h2>
        <form id="contactForm" action=<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "#contact"); ?> method="POST" novalidate>

            <!-- Name Field --> 
            <div class="form-group">
            <span style="color:maroon;"><?php echo $nameErr;?></span>
            <input type="text" class="feedback-input" id="name" placeholder="Name" name="name" value="<?php if (isset($name)) {echo $name;} ?>" />
            </div>
            
            <!-- Email Field -->
            <div class="form-group">
            <span style="color:maroon;"><?php echo $emailErr;?></span>
            <input type="email" class="feedback-input" id="email" placeholder="Email" name="email" value="<?php if (isset($email)) {echo $email;} ?>" />
            </div>

            <!-- Message Field -->
            <div class="form-group">
            <textarea id="comments" class="feedback-input" name="comments" placeholder="Comments"><?php if (isset($comment)) {echo $comment;} ?></textarea>
            </div>


            <!-- Submit Button -->
            <input type="submit" value="SUBMIT"/>


        </form>  
        </section>
    
        <br><br>

        <footer>
            <div class="top-page"><a href="#Home-Page-Top">Back To Top↑</a></div>
            <p>Copyright &copy; 2022 bkteel</p>  
        </footer>
        
    </div>
</body>

</html>