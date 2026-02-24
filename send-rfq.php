<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $to = "operations@ukinboundgroundtransport.com";
    $subject = "New RFQ Submission - UK Inbound Ground Transport";

    $company = htmlspecialchars($_POST['company']);
    $contact = htmlspecialchars($_POST['contact']);
    $email = htmlspecialchars($_POST['email']);
    $departure_dates = htmlspecialchars($_POST['departure_dates']);
    $passenger_volume = htmlspecialchars($_POST['passenger_volume']);
    $gateway = htmlspecialchars($_POST['gateway']);
    $multi_region = htmlspecialchars($_POST['multi_region']);

    $message = "
New RFQ Submission:

Company: $company
Contact Name: $contact
Email: $email
Departure Dates: $departure_dates
Passenger Volume: $passenger_volume
Gateway: $gateway
Multi-Region Touring: $multi_region
";

    $headers = "From: noreply@ukinboundgroundtransport.com\r\n";
    $headers .= "Reply-To: $email\r\n";

    // Handle file upload
    if (isset($_FILES['itinerary']) && $_FILES['itinerary']['error'] == 0) {

        $file_tmp = $_FILES['itinerary']['tmp_name'];
        $file_name = $_FILES['itinerary']['name'];
        $file_type = $_FILES['itinerary']['type'];

        $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));
        $boundary = md5(time());

        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n\r\n";

        $body = "--$boundary\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $body .= $message . "\r\n";

        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n";
        $body .= "Content-Disposition: attachment\r\n\r\n";
        $body .= $file_content . "\r\n";
        $body .= "--$boundary--";

        mail($to, $subject, $body, $headers);

    } else {

        mail($to, $subject, $message, $headers);
    }

    echo "<h2>Thank you. Your programme has been submitted for operational review.</h2>";
}
?>
