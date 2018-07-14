<!DOCTYPE html>
<html>
<head>
<title>Devis</title>
<style type="text/css">
  body{
      padding: 0 !important;
      margin: 0 !important;
      font-family: 'Helvetica';
    }
  </style>
</head>
<body class="devis-pdf">
  <table>
    <tr>
      <td><h1 style="font-family: helvetica; color: #343a40;">Dear <?= $paymentPDF['username'] ?>,</h1></td>
    </tr>
    <tr>
      <td style="padding-top: 50px;">
        <p style="font-family: helvetica; color: #343a40; font-size: 18px;">
          Thank you for your trust. <br>
          Here is your bill for the payment you made on <span style="font-size: 20px; font-weight: bold; color: #ffb300;">Spo(r)ts</span> for you company account.
        </p>
      </td>
    </tr>
    <tr>
      <td style="padding-top: 50px;">
        <table style="border-collapse: collapse;">
          <tr>
            <td style="padding: 10px; color: #343a40; font-size: 18px; font-weight: bold; border: 1px solid #343a40;">Order ID</td>
            <td style="padding: 10px; color: #343a40; font-size: 18px; font-weight: bold; border: 1px solid #343a40;">Payer ID</td>
            <td style="padding: 10px; color: #343a40; font-size: 18px; font-weight: bold; border: 1px solid #343a40;">Payment ID</td>
            <td style="padding: 10px; color: #343a40; font-size: 18px; font-weight: bold; border: 1px solid #343a40;">Date</td>
            <td style="padding: 10px; color: #343a40; font-size: 18px; font-weight: bold; border: 1px solid #343a40;">Amount</td>
          </tr>
          <tr>
            <td style="padding: 10px; color: #343a40; font-size: 18px; border: 1px solid #343a40;"><?= $paymentPDF['order_id'] ?></td>
            <td style="padding: 10px; color: #343a40; font-size: 18px; border: 1px solid #343a40;"><?= $paymentPDF['payer_id'] ?></td>
            <td style="padding: 10px; color: #343a40; font-size: 18px; border: 1px solid #343a40;"><?= $paymentPDF['payment_id'] ?></td>
            <td style="padding: 10px; color: #343a40; font-size: 18px; border: 1px solid #343a40;"><?= $paymentPDF['date'] ?></td>
            <td style="padding: 10px; color: #343a40; font-size: 18px; border: 1px solid #343a40;"><?= $paymentPDF['amount'] ?> €</td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td style="padding-top: 50px;">
        <p style="color: #343a40; font-size: 18px;">
          You just added <span style="font-weight: bold;">One month</span> to your company account. <br>
          See you soon on Spo(r)ts! <br> <br>
          <span style="font-size: 20px; font-weight: bold; color: #ffb300;">Spo(r)ts</span> team.
        </p>
      </td>
    </tr>
  </table>
</body>