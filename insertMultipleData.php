<?php
// Database connection
require_once "./config.php";

// Number of dummy records to insert
$num_records = 500;

// Dummy data arrays
$first_names = ["John", "Jane", "Alice", "Michael", "Emma", "David", "Olivia", "James", "Sophia", "Daniel"];
$last_names = ["Smith", "Johnson", "Brown", "Williams", "Jones", "Garcia", "Martinez", "Hernandez", "Young", "Lee"];
$emails = ["example1@example.com", "example2@example.com", "example3@example.com", "example4@example.com", "example5@example.com"];
$genders = ["Male", "Female"];
$designations = ["Manager", "Engineer", "Developer", "Salesperson", "Accountant", "HR"];

// Generate and execute insert queries
for ($i = 1; $i <= $num_records; $i++) {
    // Random data for each record
    $first_name = $first_names[array_rand($first_names)];
    $last_name = $last_names[array_rand($last_names)];
    $email = $emails[array_rand($emails)];
    $age = rand(20, 60);
    $gender = $genders[array_rand($genders)];
    $designation = $designations[array_rand($designations)];
    $joining_date = date('Y-m-d', strtotime("-$i days")); // Assigning a different joining date for each record

    // Insert query
    $sql = "INSERT INTO employees (first_name, last_name, email, age, gender, designation, joining_date) VALUES ('$first_name', '$last_name', '$email', $age, '$gender', '$designation', '$joining_date')";

    // Execute query
    if (mysqli_query($link, $sql)) {
        echo " $i Record inserted successfully<br>";
    } else {
        echo "Error inserting record: " . mysqli_error($link) . "<br>";
    }
}
echo "$num_records Total Records Inserted Successfully";

// Close connection
mysqli_close($link);
?>


   <!-- Filter inputs -->
   <div class="mb-3 row">
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterFirstName" placeholder="Filter First Name">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterLastName" placeholder="Filter Last Name">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterEmail" placeholder="Filter Email">
      </div>
      <div class="col-md-1">
        <input type="text" class="form-control" id="filterAge" placeholder="Filter Age">
      </div>
      <div class="col-md-1">
        <select class="form-select" id="filterGender">
          <option value="">Filter Gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterRole" placeholder="Filter Role">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterJoiningDate" placeholder="Filter Joining Date">
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary" id="applyFilter">Apply Filter</button>
      </div>
    </div>
