<html>
    <head>
        <title>Update Contact Details</title>
    </head>
    <body>
        <h2>Update Contact Details</h2>
		<?php echo form_open('Home/getSelectedContactDetails/'); ?>
           <?php echo $contact_details; ?>
           <p><input type="submit" name="update" value="Update Contact Details"></p>
				
        </form>               
    </body>
</html>