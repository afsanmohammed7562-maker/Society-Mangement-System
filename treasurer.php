<?php
include 'db.php';

// Fetch all members for the payment history filter dropdown
$members_result = mysqli_query($conn, "SELECT reg_no, fullname FROM members ORDER BY fullname ASC");
$members = [];
while ($m_row = mysqli_fetch_assoc($members_result)) {
    $members[] = $m_row;
}

// Determine which member's payment history to display
$selected_reg_no = $_GET['reg_no'] ?? '';
if (empty($selected_reg_no) && !empty($members)) {
    $selected_reg_no = $members[0]['reg_no'];
}

// Fetch payment history for the selected member
$payments = [];
if (!empty($selected_reg_no)) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM payments WHERE reg_no = ? ORDER BY id ASC");
    mysqli_stmt_bind_param($stmt, "s", $selected_reg_no);
    mysqli_stmt_execute($stmt);
    $payments_result = mysqli_stmt_get_result($stmt);
    while ($p_row = mysqli_fetch_assoc($payments_result)) {
        $payments[] = $p_row;
    }
}

// Fetch all monthly reports
$reports_result = mysqli_query($conn, "SELECT * FROM monthly_reports ORDER BY id DESC");
$reports = [];
while ($r_row = mysqli_fetch_assoc($reports_result)) {
    $reports[] = $r_row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Treasurer Section</title>

  <!-- CSS -->
  <link rel="stylesheet" href="Treserer.css">
  <link rel="stylesheet" href="footer.css">

  <!-- Google Font & Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  
  <style>
    /* Styling adjustments to blend with navbar and footer */
    .treasurer-section {
      min-height: calc(100vh - 280px);
    }
    
    .filter-box {
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 25px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.03);
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .filter-box label {
      font-weight: 600;
      color: #2c3440;
    }

    .filter-box select {
      padding: 10px 15px;
      border: 1px solid #ddd;
      border-radius: 6px;
      outline: none;
      font-family: 'Poppins', sans-serif;
      font-size: 14px;
      cursor: pointer;
    }

    .card-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 20px;
    }

    .report-card {
      width: 100%;
    }
  </style>

</head>
<body>

  <!-- Reusable Navigation Bar -->
  <?php include 'navbar.php'; ?>

  <!-- TREASURER SECTION -->
  <section class="treasurer-section">

    <!-- HEADING -->
    <div class="heading" style="margin-top: 20px;">
      <i class="fa-solid fa-coins"></i>
      <h1>Treasurer Section</h1>
    </div>

    <!-- TABS -->
    <div class="tabs">
      <button id="monthlyBtn" class="active-tab">
        Monthly Reports
      </button>

      <button id="paymentBtn">
        Payment Histories
      </button>
    </div>

    <!-- MONTHLY REPORTS -->
    <div id="monthlyReports">
      <div class="card-container">
        <?php if (!empty($reports)): ?>
          <?php foreach ($reports as $report): ?>
            <div class="report-card">
              <div class="card-icon">
                <i class="fa-solid fa-chart-pie"></i>
              </div>
              <h3><?php echo htmlspecialchars($report['month']); ?></h3>
              <!-- Download targets report_file (defaults to sam.pdf) -->
              <a href="<?php echo htmlspecialchars($report['report_file']); ?>" download class="download-btn" style="display: block; text-decoration: none; text-align: center;">
                Download
              </a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="color: #777;">No monthly reports uploaded yet.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- PAYMENT HISTORY -->
    <div id="paymentHistory" style="display:none;">
      
      <!-- Filter Member Selector -->
      <div class="filter-box">
        <label for="memberFilter"><i class="fa-solid fa-user-filter"></i> Select Member:</label>
        <select id="memberFilter" onchange="window.location.href='treasurer.php?reg_no=' + this.value + '#paymentHistoryTab'">
          <?php foreach ($members as $member): ?>
            <option value="<?php echo htmlspecialchars($member['reg_no']); ?>" <?php echo ($selected_reg_no == $member['reg_no']) ? 'selected' : ''; ?>>
              <?php echo htmlspecialchars($member['fullname'] . ' (' . $member['reg_no'] . ')'); ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <table>
        <thead>
          <tr>
            <th>Month</th>
            <th>Amount</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($payments)): ?>
            <?php foreach ($payments as $payment): ?>
              <tr>
                <td><?php echo htmlspecialchars($payment['month']); ?></td>
                <td>Rs. <?php echo number_format($payment['amount'], 2); ?></td>
                <td class="<?php echo ($payment['status'] == 'Paid') ? 'paid' : 'pending'; ?>">
                  <?php echo htmlspecialchars($payment['status']); ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3" style="text-align: center; color: #777;">No payment records found for this member.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

    </div>

  </section>

  <!-- Reusable Footer -->
  <?php include 'footer.php'; ?>

  <!-- JAVASCRIPT FOR TABS -->
  <script>
    const monthlyBtn = document.getElementById("monthlyBtn");
    const paymentBtn = document.getElementById("paymentBtn");

    const monthlyReports = document.getElementById("monthlyReports");
    const paymentHistory = document.getElementById("paymentHistory");

    // Handle hash routing to switch tab automatically on page reload
    if (window.location.hash === "#paymentHistoryTab") {
      switchToPayments();
    }

    monthlyBtn.addEventListener("click", () => {
      switchToReports();
      // Remove hash from url
      history.replaceState(null, null, ' ');
    });

    paymentBtn.addEventListener("click", () => {
      switchToPayments();
      window.location.hash = "paymentHistoryTab";
    });

    function switchToReports() {
      monthlyReports.style.display = "block";
      paymentHistory.style.display = "none";
      monthlyBtn.classList.add("active-tab");
      paymentBtn.classList.remove("active-tab");
    }

    function switchToPayments() {
      monthlyReports.style.display = "none";
      paymentHistory.style.display = "block";
      paymentBtn.classList.add("active-tab");
      monthlyBtn.classList.remove("active-tab");
    }
  </script>

</body>
</html>
