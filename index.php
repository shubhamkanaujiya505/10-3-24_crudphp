<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.1/font/bootstrap-icons.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
  <link rel="stylesheet" href="./style.css">
  <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon">
  <title>PHP CRUD Operations</title>
</head>

<body>
  <div class="container">
    <div class="py-4">
      <a href="./create.php" class="btn btn-secondary">
        <i class="bi bi-plus-circle-fill"></i> Add Employee
      </a>
    </div>
     <!-- Filter buttons and inputs -->
     <div class="col-md-2">
      <div class="input-group">
      <select class="form-select" id="filterFirstName" aria-label="First Name">
          <option value="">First Name</option>
          <?php
          # Include connection
          require_once "./config.php";

          # Fetch distinct first names from the database
          $sql = "SELECT DISTINCT first_name FROM employees";
          if ($result = mysqli_query($link, $sql)) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo "<option value='" . $row['first_name'] . "'>" . $row['first_name'] . "</option>";
            }
            mysqli_free_result($result);
          }
          ?>
        </select>
        <div class="col-md-2">
          <div class="input-group">
            <select class="form-select" id="filterLastName" aria-label="Last Name">
              <option value="">Last Name</option>
              <?php
              require_once "./config.php";
              $sql = "SELECT DISTINCT last_name FROM employees" ;
              if( $result = mysqli_query($link, $sql)){
                while($row = mysqli_fetch_array($result)){
                  echo "<option value='" . $row['last_name'] . "'>" . $row['last_name'] . "</option>";
                }
                mysqli_free_result($result);
              }
              ?>
            </select>
          </div>
        </div>
        <button class="btn btn-primary" id="applyFilterBtn">Apply Filter</button>
      </div>
    </div>

    <!-- Table starts here -->
    <table class="table table-bordered table-striped align-middle">
      <thead>
        <tr>
          <th>id</th>
          <th>First Name <input type="text" class="form-control form-control-sm" id="filterFirstName" placeholder="Filter"></th>
          <th>Last Name <input type="text" class="form-control form-control-sm" id="filterLastName" placeholder="Filter"></th>
          <th>Email Address <input type="text" class="form-control form-control-sm" id="filterEmail" placeholder="Filter"></th>
          <th>Age <input type="text" class="form-control form-control-sm" id="filterAge" placeholder="Filter"></th>
          <th>Gender <input type="text" class="form-control form-control-sm" id="filterGender" placeholder="Filter"></th>
          <th>Role <input type="text" class="form-control form-control-sm" id="filterRole" placeholder="Filter"></th>
          <th>Joining Date <input type="text" class="form-control form-control-sm" id="filterJoiningDate" placeholder="Filter"></th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      <?php
        # Include connection
        require_once "./config.php";

        # Define default SQL query
        $sql = "SELECT * FROM employees";

        # Fetch filter criteria if set
        $filters = array();
        if (isset($_GET['filterFirstName']) && !empty($_GET['filterFirstName'])) {
          $filterFirstNames = explode(',', $_GET['filterFirstName']); // Splitting multiple names
          $filterFirstNames = array_map(function($name) use ($link) {
            return mysqli_real_escape_string($link, trim($name));
          }, $filterFirstNames); // Escape special characters

          $filterConditions = array();
          foreach ($filterFirstNames as $name) {
            $filterConditions[] = "first_name LIKE '%" . $name . "%'";
          }
          $filters[] = "(" . implode(" OR ", $filterConditions) . ")";
        }

        # Apply filters to the SQL query
        if(!empty($filters)) {
            $sql .= " WHERE " . implode(" AND ", $filters);
        }

        if ($result = mysqli_query($link, $sql)) {
          if (mysqli_num_rows($result) > 0) {
            $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $count = 1;
            foreach ($rows as $row) { ?>
              <tr>
                <td><?= $count++; ?></td>
                <td><?= $row["first_name"]; ?></td>
                <td><?= $row["last_name"]; ?></td>
                <td><?= $row["email"]; ?></td>
                <td><?= $row["age"]; ?></td>
                <td><?= $row["gender"]; ?></td>
                <td><?= $row["designation"]; ?></td>
                <td><?= $row["joining_date"]; ?></td>
                <td>
                  <a href="./update.php?id=<?= $row["id"]; ?>" class="btn btn-primary btn-sm">
                    <i class="bi bi-pencil-square"></i>
                  </a>&nbsp;
                  <a href="./delete.php?id=<?= $row["id"]; ?>" class="btn btn-danger btn-sm">
                    <i class="bi bi-trash-fill"></i>
                  </a>
                </td>
              </tr>
            <?php
            }
            # Free result set
            mysqli_free_result($result);
          } else { ?>
            <tr>
              <td class="text-center text-danger fw-bold" colspan="9">** No records were found **</td>
            </tr>
        <?php
          }
        }
        # Close connection
        mysqli_close($link);
        ?>
      </tbody>
    </table>
  </div>

  <script>
    const delBtnEl = document.querySelectorAll(".btn-danger");
    delBtnEl.forEach(function(delBtn) {
      delBtn.addEventListener("click", function(e) {
        const message = confirm("Are you sure you want to delete this record?");
        if (message == false) {
          e.preventDefault();
        }
      });
    });
  </script>
   <script>
    document.getElementById("applyFilterBtn").addEventListener("click", function() {
      const filterFirstName = document.getElementById("filterFirstName").value;
      // Redirect to the same page with filter parameters
      window.location.href = window.location.pathname + "?filterFirstName=" + filterFirstName;
    });
  </script>
</body>

</html>