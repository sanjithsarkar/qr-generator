<?php

include_once($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');

$db_host = 'localhost';
$db_username = 'root';
$db_password = '12345678';
$db_name = 'qr_generator';

$conn = mysqli_connect($db_host, $db_username, $db_password, $db_name);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$id = get_query_var('qr_id');

$query = "SELECT * FROM wp_userdata WHERE id = $id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) > 0) {

    $user_data = mysqli_fetch_assoc($result);
//    echo "<p>User ID: " . $user_data['user_id'] . "</p>";
} else {
    echo "User not found";
}

// Close the database connection
mysqli_close($conn);
?>

    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="bg-white py-8">
        <div class="max-w-screen-md mx-auto">
            <div class="bg-white shadow-lg rounded-lg">
                <div class="bg-blue-500 text-white py-4 px-6 rounded-t-lg">
                    <h3 class="text-2xl font-semibold">User Profile</h3>
                </div>
                <div class="py-10">
                    <div class="grid grid-cols-5">
                        <div class="col-span-1">

                        </div>
                        <div class="col-span-3 flex justify-center items-center border-collapse border border-gray-300">
                            <div class="flex justify-center">
                                <div class="w-full py-6">
                                    <div class=" rounded-full p-2 mx-auto">
                                        <img src="https://example.com/path-to-your-image/avatar-15.png" alt=""
                                             class="block w-32 h-32 mx-auto">
                                    </div>
                                    <div class="text-center mt-4">
                                        <span class="text-lg font-semibold"><?php echo $user_data['name']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-1">

                        </div>
                    </div>

                    <div class="grid grid-cols-5">
                        <div class="col-span-1">

                        </div>

                        <div class="col-span-3 flex justify-center items-center mt-4 border-collapse border border-gray-300">
                            <div class="w-full">
                                <table class="w-full table-fixed">
                                    <tbody>
                                    <tr class="bg-gray-100">
                                        <td class="w-1/2 py-2 px-4 font-semibold">Name</td>
                                        <td class="w-1/2 py-2 px-4"><?php echo $user_data['name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="w-1/2 py-2 px-4 font-semibold">Email</td>
                                        <td class="w-1/2 py-2 px-4"><?php echo $user_data['email']; ?></td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td class="w-1/2 py-2 px-4 font-semibold">Mobile</td>
                                        <td class="w-1/2 py-2 px-4"><?php echo $user_data['mobile']; ?></td>
                                    </tr>
                                    <tr class="bg-gray-100">
                                        <td class="w-1/2 py-2 px-4 font-semibold">Address</td>
                                        <td class="w-1/2 py-2 px-4"><?php echo $user_data['address']; ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="col-span-1">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


<?php //echo get_query_var('qr_id') ?>