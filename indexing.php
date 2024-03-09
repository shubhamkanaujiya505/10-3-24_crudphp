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

    <!-- Filter inputs -->
    <div class="mb-3 row">
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterFirstName" placeholder="First Name">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterLastName" placeholder="Last Name">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterEmail" placeholder="Email">
      </div>
      <div class="col-md-1">
        <input type="text" class="form-control" id="filterAge" placeholder="Age">
      </div>
      <div class="col-md-1">
        <select class="form-select" id="filterGender">
          <option value="">Gender</option>
          <option value="Male">Male</option>
          <option value="Female">Female</option>
        </select>
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterRole" placeholder="Role">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" id="filterJoiningDate" placeholder="Joining Date">
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary" id="applyFilter">Apply Filter</button>
      </div>
    </div>

    <!-- Table starts here -->
    <table class="table table-bordered table-striped align-middle">
      <thead>
        <tr>
          <th>Id</th>
          <th>First Name</th>
          <th>Last Name</th>
          <th>Email Address</th>
          <th>Age</th>
          <th>Gender</th>
          <th>Role</th>
          <th>Joining Date</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        # Include connection
        require_once "./config.php";

         # Pagination configuration
         $records_per_page = 10;
         $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
         $offset = ($page - 1) * $records_per_page;
 
         # Define default SQL query with pagination
         $sql = "SELECT * FROM employees LIMIT $offset, $records_per_page";

        # Fetch filter criteria if set
        $filters = array();
        if(isset($_GET['filterFirstName']) && !empty($_GET['filterFirstName'])) {
            $filters[] = "first_name LIKE '%" . $_GET['filterFirstName'] . "%'";
        }
        if(isset($_GET['filterLastName']) && !empty($_GET['filterLastName'])) {
            $filters[] = "last_name LIKE '%" . $_GET['filterLastName'] . "%'";
        }
        if(isset($_GET['filterEmail']) && !empty($_GET['filterEmail'])) {
            $filters[] = "email LIKE '%" . $_GET['filterEmail'] . "%'";
        }
        if(isset($_GET['filterAge']) && !empty($_GET['filterAge'])) {
            $filters[] = "age = " . $_GET['filterAge'];
        }
        if(isset($_GET['filterGender']) && !empty($_GET['filterGender'])) {
            $filters[] = "gender = '" . $_GET['filterGender'] . "'";
        }
        if(isset($_GET['filterRole']) && !empty($_GET['filterRole'])) {
            $filters[] = "role LIKE '%" . $_GET['filterRole'] . "%'";
        }
        if(isset($_GET['filterJoiningDate']) && !empty($_GET['filterJoiningDate'])) {
            $filters[] = "joining_date LIKE '%" . $_GET['filterJoiningDate'] . "%'";
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
                <td><?= $row["id"]; ?></td>
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
         # Count total pages
         $total_sql = "SELECT COUNT(*) AS total FROM employees";
         $total_result = mysqli_query($link, $total_sql);
         $total_row = mysqli_fetch_assoc($total_result);
         $total_pages = ceil($total_row["total"] / $records_per_page);
        // # Close connection
        // mysqli_close($link); // Remove this line to keep the connection open for pagination
        ?>
      </tbody>
    </table>
    <!-- Pagination links -->
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <?php if ($page > 1): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a></li>
        <?php endif; ?>
        <?php
        $start_page = max(1, $page - 2);
        $end_page = min($total_pages, $start_page + 3);

         if ($start_page > 1): ?>
          <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
          <?php if ($start_page > 2): ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
          <?php endif;
        endif;

        for ($i = $start_page; $i <= $end_page; $i++):
        ?>
          <li class="page-item <?= $i == $page ? 'active' : '' ?>"><a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a></li>
        <?php endfor;

        if ($end_page < $total_pages): ?>
          <?php if ($end_page < $total_pages - 1): ?>
            <li class="page-item disabled"><span class="page-link">...</span></li>
          <?php endif; ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $total_pages ?>"><?= $total_pages ?></a></li>
        <?php endif;

        if ($page < $total_pages): ?>
          <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>

  <script>
    document.getElementById('applyFilter').addEventListener('click', function() {
      let queryParams = new URLSearchParams(window.location.search);

      const filterFirstName = document.getElementById('filterFirstName').value;
      const filterLastName = document.getElementById('filterLastName').value;
      const filterEmail = document.getElementById('filterEmail').value;
      const filterAge = document.getElementById('filterAge').value;
      const filterGender = document.getElementById('filterGender').value;
      const filterRole = document.getElementById('filterRole').value;
      const filterJoiningDate = document.getElementById('filterJoiningDate').value;

      queryParams.set('filterFirstName', filterFirstName);
      queryParams.set('filterLastName', filterLastName);
      queryParams.set('filterEmail', filterEmail);
      queryParams.set('filterAge', filterAge);
      queryParams.set('filterGender', filterGender);
      queryParams.set('filterRole', filterRole);
      queryParams.set('filterJoiningDate', filterJoiningDate);

      window.location.search = queryParams.toString();
    });
  </script>
</body>

</html>
