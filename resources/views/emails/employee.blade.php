<!-- resources/views/emails/contract.blade.php -->


<p>Hello CopoNice</p>

<p> Here are the details of {{ $data['userName'] }}'s company :</p>

<ul>
    <li>Email: {{ $data['email'] }}</li>
    <li>Phone: {{ $data['phone'] }}</li>
    <li>Company Name: {{ $data['companyName'] }}</li>
    <li>Message Title: {{ $data['messageTitle'] }}</li>
    <li>Message Content: {{ $data['messageContent'] }}</li>
</ul>

<p>Best regards,</p>