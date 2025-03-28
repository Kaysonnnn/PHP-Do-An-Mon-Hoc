<?php require_once __DIR__ . '/../../partials/customer-header.php'; ?>

<style>
  #card-element {
    border: 1px solid #ced4da;
    border-radius: 4px;
    padding: 10px;
    background-color: #f8f9fa;
    font-size: 16px;
    font-family: Arial, sans-serif;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
    transition: border-color 0.3s ease-in-out;
  }

  #card-element:focus {
    border-color: #80bdff;
    outline: none;
    box-shadow: 0 0 8px rgba(128, 189, 255, 0.5);
  }

  #submit {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 4px;
    background-color: #28a745;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  #submit:hover {
    background-color: #218838;
  }

  #payment-message {
    color: #28a745;
    font-weight: bold;
  }

  .error-message {
    color: #dc3545;
    margin-top: 10px;
  }

</style>

<div class="container mt-5">
  <h2>Thanh toán với Stripe</h2>
  <table class="table">
    <thead>
    <tr>
      <th>Hình</th>
      <th>Têm</th>
      <th>Đơn giá</th>
      <th>Số lượng</th>
      <th>Tổng</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $grandTotal = 0;
    foreach ($cart as $item):
      $total = $item['price'] * $item['quantity'];
      $grandTotal += $total;
      ?>
      <tr>
        <td><img src="/doan/uploads/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 50px;"></td>
        <td><?= htmlspecialchars($item['name']) ?></td>
        <td>$<?= number_format($item['price'], 2) ?></td>
        <td><?= $item['quantity'] ?></td>
        <td>$<?= number_format($total, 2) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <h4>Tổng cộng: $<?= number_format($grandTotal, 2) ?></h4>
  <form id="payment-form" class="mt-4">
    <div id="card-element"></div>
    <div id="error-message" class="error-message"></div>
    <button id="submit" class="btn btn-success mt-3">Thanh toán $<?= number_format($grandTotal, 2) ?></button>
  </form>
  <div id="payment-message" class="mt-3"></div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
  const stripe = Stripe('<?= $publishableKey ?>');
  const elements = stripe.elements();

  const style = {
    base: {
      color: '#32325d',
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSmoothing: 'antialiased',
      fontSize: '16px',
      '::placeholder': {
        color: '#aab7c4',
      },
    },
    invalid: {
      color: '#fa755a',
      iconColor: '#fa755a',
    },
  };

  const cardElement = elements.create('card', { style });
  cardElement.mount('#card-element');

  cardElement.on('change', (event) => {
    const message = document.getElementById('error-message');
    if (event.error) {
      message.textContent = event.error.message;
    } else {
      message.textContent = '';
    }
  });

  const form = document.getElementById('payment-form');
  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const { error, paymentIntent } = await stripe.confirmCardPayment('<?= $clientSecret ?>', {
      payment_method: {
        card: cardElement,
      }
    });

    if (error) {
      document.getElementById('error-message').textContent = error.message;
    } else {
      document.getElementById('payment-message').textContent = "Payment Successful!";
      alert("Thanh toán thành công!");
      window.location.href = '/doan/customer/checkout/confirm-stripe';
    }
  });
</script>

<?php require_once __DIR__ . '/../../partials/customer-footer.php'; ?>
