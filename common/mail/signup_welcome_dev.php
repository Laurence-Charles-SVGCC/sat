<html>
	<head>
	</head>
	<body>
        <table width="700">
            <tbody>
                <tr>
                	<td width="45"></td>
                    <td>
                    	<p><?php echo date("l F j, Y"); ?></p>
                        <p>Dear <?php echo $firstname . ' ' . $lastname ?>,</p>
                        <p>Thank you for signing up to SVGCC Administrative Terminal (SAT)!</p>
                        <p>You can access the (Development) <a href="dev.satadmin.svgcc.net"> SAT Administrative Console </a> and the 
                            <a href="dev.sat.svgcc.net"> SAT Frontend </a> with the following credentials:</p>
                        <p>Username: <?php echo $username ?></p>
                        <p>Partial Password: <?php echo $password ?></p>
                        <p><strong>N.B: This is only a part of the password chosen. Please use the entire one when signing in.</strong></p>
                        
                        <p>Regards,<br />
                        SVGCC SAT Admin Team</p>
                    </td>
                </tr>
            </tbody>
		</table>
	</body>
</html>
