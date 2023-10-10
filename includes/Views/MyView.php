<?php

namespace WPPluginWithVueTailwind\Views;
class UserProfileView
{
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function renderProfileView($id)
    {
        $table_name = $this->wpdb->prefix . QR_GENERATOR_SLUG . '_' . 'userdata';

        $columns = ['id', 'qr_name', 'name', 'surname', 'title', 'email', 'mobile', 'address', 'image'];

        $sql = $this->wpdb->prepare("SELECT " . implode(', ', $columns) . " FROM $table_name WHERE id = %d", $id);
        $results = $this->wpdb->get_results($sql);

        if (count($results) > 0) {
            $viewResults = $results[0];
            $imageUrl = $viewResults->image;
        } else {
            $viewResults = null;
            $imageUrl = '';
        }

        // HTML rendering code
        ?>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

        <div class="bg-white py-8">
            <div class="max-w-screen-md mx-auto">
                <div class="bg-white shadow-lg rounded-lg">
                    <div class="bg-black text-white py-4 px-6 rounded-t-lg">
                        <h3 class="text-2xl font-semibold">View Profile</h3>
                    </div>
                    <div class="py-10">
                        <div class="grid grid-cols-6">
                            <div class="col-span-1"></div>
                            <div class="col-span-4 flex justify-center items-center rounded-lg border-collapse border border-gray-300">
                                <div class="flex justify-center">
                                    <div class="w-full py-6">
                                        <div class=" rounded-full p-2 mx-auto">
                                            <img src="<?php echo $imageUrl; ?>" alt="No Image"
                                                 class="block w-32 h-32 mx-auto rounded-full">
                                        </div>
                                        <div class="text-center mt-4">
                                            <span class="text-lg font-semibold"><?php echo $viewResults->name; ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-1"></div>
                        </div>

                        <div class="grid grid-cols-6">
                            <div class="col-span-1"></div>
                            <div class="col-span-4 flex justify-center items-center rounded py-4 mt-4 border-collapse border border-gray-300">
                                <div class="w-full">
                                    <table class="w-full table-fixed">
                                        <tbody>
                                        <tr class="bg-gray-100">
                                            <td class="w-1/2 py-2 px-4 font-semibold pl-12">Name</td>
                                            <td class="w-1/2 py-2 px-4"><?php echo $viewResults->name; ?></td>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="w-1/2 py-2 px-4 font-semibold pl-12">Email</td>
                                            <td class="w-1/2 py-2 px-4"><?php echo $viewResults->email; ?></td>
                                        </tr>
                                        <tr class="bg-gray-100">
                                            <td class="w-1/2 py-2 px-4 font-semibold pl-12">Mobile</td>
                                            <td class="w-1/2 py-2 px-4"><?php echo $viewResults->mobile; ?></td>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <td class="w-1/2 py-2 px-4 font-semibold pl-12">Address</td>
                                            <td class="w-1/2 py-2 px-4"><?php echo $viewResults->address; ?></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-span-1"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

// Usage
$userProfileView = new UserProfileView();
$id = get_query_var('qr_id');
$userProfileView->renderProfileView($id);
?>
