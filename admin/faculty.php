<?php include('db_connect.php'); ?>

<div class="container-fluid">
<style>
    input[type=checkbox] {
        /* Double-sized Checkboxes */
        -ms-transform: scale(1.5); /* IE */
        -moz-transform: scale(1.5); /* FF */
        -webkit-transform: scale(1.5); /* Safari and Chrome */
        -o-transform: scale(1.5); /* Opera */
        transform: scale(1.5);
        padding: 10px;
    }
</style>
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12">
                
            </div>
        </div>
        <div class="row">
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Faculty List</b>
                        <span class="">

                            <button class="btn btn-primary btn-block btn-sm col-sm-2 float-right" type="button" id="new_faculty">
                                <i class="fa fa-plus"></i> New</button>
                        </span>
                    </div>
                    <div class="card-body">
                        
                        <table class="table table-bordered table-condensed table-hover">
                            <colgroup>
                                <col width="5%">
                                <col width="20%">
                                <col width="30%">
                                <col width="20%">
                                <col width="10%">
                                <col width="15%">
                                <col width="10%"> <!-- New column for upload image -->
                                <col width="15%"> <!-- New column for uploaded image -->
                            </colgroup>
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="">ID No</th>
                                    <th class="">Name</th>
                                    <th class="">Email</th>
                                    <th class="">Contact</th>
                                    <th class="text-center">Action</th>
                                    <th class="text-center">Upload Image</th> <!-- New header -->
                                    <th class="text-center">Uploaded Image</th> <!-- New header -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $faculty =  $conn->query("SELECT *, concat(lastname,', ', firstname, ' ', middlename) as name from faculty order by concat(lastname,', ',firstname,' ',middlename) asc");
                                while($row = $faculty->fetch_assoc()):
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td class="">
                                         <p><b><?php echo $row['id_no'] ?></b></p>
                                    </td>
                                    <td class="">
                                         <p><b><?php echo ucwords($row['name']) ?></b></p>
                                    </td>
                                    <td class="">
                                         <p><b><?php echo $row['email'] ?></b></p>
                                    </td>
                                    <td class="text-right">
                                         <p><b><?php echo $row['contact'] ?></b></p>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-primary view_faculty" type="button" data-id="<?php echo $row['id'] ?>" >View</button>
                                        <button class="btn btn-sm btn-outline-primary edit_faculty" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
                                        <button class="btn btn-sm btn-outline-danger delete_faculty" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
                                    </td>
                                    <!-- New column with upload button -->
                                    <td class="text-center">
                                        <div id="image_preview_<?php echo $row['id'] ?>" class="image-preview-container" style="display: none;">
                                            <img id="image_display_<?php echo $row['id'] ?>" src="<?php echo $row['image']; ?>" alt="Image Preview" style="max-width: 150px; max-height: 150px;"/>
                                        </div>
                                        <input type="file" id="upload_image_<?php echo $row['id'] ?>" class="upload_image" data-id="<?php echo $row['id'] ?>" accept="image/*" style="display:none"/>
                                        <button class="btn btn-sm btn-outline-success upload_image_btn" type="button" data-id="<?php echo $row['id'] ?>">Upload Image</button>
                                    </td>
                                    <!-- New column to show the uploaded image -->
                                    <td class="text-center" id="uploaded_image_<?php echo $row['id']; ?>">
    									<?php if(!empty($row['image'])): ?>
        								<img src="<?php echo $row['image']; ?>" style="max-width: 150px; max-height: 150px;">
    								<?php else: ?>
        							<p>No image</p>
   									<?php endif; ?>
									</td>

                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>    

</div>

<style>
    td {
        vertical-align: middle !important;
    }
    td p {
        margin: unset;
    }
    img {
        max-width: 100px;
        max-height: 150px;
    }
    .image-preview-container {
        margin-bottom: 10px;
    }
</style>

<script>
    $(document).ready(function() {
        $('table').dataTable();
    });

    $('#new_faculty').click(function() {
        uni_modal("New Entry", "manage_faculty.php", 'mid-large');
    });

    $('.view_faculty').click(function() {
        uni_modal("Faculty Details", "view_faculty.php?id=" + $(this).attr('data-id'), '');
    });

    $('.edit_faculty').click(function() {
        uni_modal("Manage Job Post", "manage_faculty.php?id=" + $(this).attr('data-id'), 'mid-large');
    });

    $('.delete_faculty').click(function() {
        _conf("Are you sure to delete this faculty?", "delete_faculty", [$(this).attr('data-id')], 'mid-large');
    });

    $('.upload_image_btn').click(function() {
        var faculty_id = $(this).attr('data-id');
        $('#upload_image_' + faculty_id).trigger('click');
    });

    $('.upload_image').change(function() {
    var faculty_id = $(this).attr('data-id');
    var file = this.files[0];

    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#image_preview_' + faculty_id).show();
            $('#image_display_' + faculty_id).attr('src', e.target.result);
        };
        reader.readAsDataURL(file);

        // Upload the image to the server
        var formData = new FormData();
        formData.append('faculty_id', faculty_id);
        formData.append('image', file);

        $.ajax({
            url: 'upload_image.php', // PHP script to handle the upload
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response == 'success') {
                    alert("Image uploaded successfully!");

                    // Reload the entire page to reflect changes
                    location.reload();  // Refresh the page after the image upload
                } else {
                    alert("Failed to upload image.");
                }
            }
        });
    }
});



    function delete_faculty($id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_faculty',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>
