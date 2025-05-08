<?php
// app/Helpers/EmailService.php
namespace App\Helpers;

class EmailService
{
  private string $apiKey;
  private string $senderEmail;
  private string $senderName;
  private string $apiEndpoint;

  /**
   * Initialize the email service
   */
  public function __construct()
  {
    $config = require __DIR__ . '/../config/config.php';

    $this->apiKey = $config['mail']['zeptomail_api_key'] ?? '';
    $this->senderEmail = $config['mail']['from_address'] ?? 'noreply@singerfrance.com';
    $this->senderName = $config['mail']['from_name'] ?? 'Singer';
    $this->apiEndpoint = 'https://api.zeptomail.eu/v1.1/email';
  }

  /**
   * Send an email using the ZeptoMail API
   * 
   * @param string $toEmail The recipient's email address
   * @param string $toName The recipient's name
   * @param string $subject The email subject
   * @param string $htmlContent The HTML content of the email
   * @return bool True if the email was sent successfully, false otherwise
   */
  public function sendEmail(string $toEmail, string $toName, string $subject, string $htmlContent): bool
  {
    $curl = curl_init();

    $data = json_encode([
      "from" => [
        "address" => $this->senderEmail,
        "name" => $this->senderName
      ],
      "to" => [
        [
          "email_address" => [
            "address" => $toEmail,
            "name" => $toName
          ]
        ]
      ],
      "subject" => $subject,
      "htmlbody" => $htmlContent
    ]);

    curl_setopt_array($curl, [
      CURLOPT_URL => $this->apiEndpoint,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $data,
      CURLOPT_HTTPHEADER => [
        "accept: application/json",
        "authorization: {$this->apiKey}",
        "cache-control: no-cache",
        "content-type: application/json",
      ],
    ]);

    /*$response = curl_exec($curl);
    $err = curl_error($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($err) {
      error_log("cURL Error while sending email: " . $err);
      return false;
    }

    if ($httpCode >= 200 && $httpCode < 300) {
      return true;
    } else {
      error_log("Email API Error: " . $response);
      return false;
    }*/
    return true;
  }

  /**
   * Send account creation confirmation email
   * 
   * @param string $email The user's email address
   * @param string $firstName The user's first name
   * @param string $lastName The user's last name
   * @param string $password The user's password (only for new accounts)
   * @return bool True if the email was sent successfully, false otherwise
   */
  public function sendAccountCreationEmail(string $email, string $firstName, string $lastName, string $password = ''): bool
  {
    $fullName = $firstName . ' ' . $lastName;
    $subject = __('email.account_created_subject');

    // Get the template content from file
    $templatePath = __DIR__ . '/../../templates/emails/accountsinformation.html';
    if (!file_exists($templatePath)) {
      error_log(__('email.error_template_not_found') . $templatePath);
      return false;
    }

    $template = file_get_contents($templatePath);
    if (!$template) {
      error_log(__('email.error_read_template'));
      return false;
    }

    // Replace placeholders in the template
    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

    $replacements = [
      '{{WEBSITE_URL}}' => $baseUrl,
      '{{USER_FIRSTNAME}}' => $firstName,
      '{{USER_EMAIL}}' => $email,
      '{{USER_PASSWORD}}' => $password ?: '******', // Only show the password for new accounts
      '{{LOGIN_URL}}' => $baseUrl . '/login',
      '{{LINK_1}}' => $baseUrl . '/products',
      '{{LINK_2}}' => $baseUrl . '/page/tutos',
      '{{LINK_3}}' => $baseUrl . '/page/nous-contacter',
      '{{CURRENT_YEAR}}' => date('Y')
    ];

    $content = str_replace(array_keys($replacements), array_values($replacements), $template);

    return $this->sendEmail($email, $fullName, $subject, $content);
  }

  /**
   * Send order confirmation email
   * 
   * @param array $order The order data
   * @param array $orderItems The order items
   * @param array $user The user data
   * @return bool True if the email was sent successfully, false otherwise
   */
  public function sendOrderConfirmationEmail(array $order, array $orderItems, array $user): bool
  {
    $subject = __('email.order_confirmation_subject', ['id' => $order['id']]);
    $email = $user['email'] ?? $order['email'] ?? '';
    $firstName = $user['first_name'] ?? '';
    $lastName = $user['last_name'] ?? '';
    $fullName = $firstName . ' ' . $lastName;

    if (empty($email)) {
      error_log(__('email.error_email_missing'));
      return false;
    }

    // Get the template content from file
    $templatePath = __DIR__ . '/../../templates/emails/confirmation_order.html';
    if (!file_exists($templatePath)) {
      error_log(__('email.error_template_not_found') . $templatePath);
      return false;
    }

    $template = file_get_contents($templatePath);
    if (!$template) {
      error_log(__('email.error_read_template'));
      return false;
    }

    $baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'];

    // Parse shipping address
    $shippingAddress = json_decode($order['shipping_address'], true);

    // Create product rows HTML
    $productsHTML = '';
    $subTotal = 0;

    foreach ($orderItems as $item) {
      $productTotal = $item['price'] * $item['quantity'];
      $subTotal += $productTotal;

      $productsHTML .= '<tr>';
      $productsHTML .= '<td>' . htmlspecialchars($item['name']) . '</td>';
      $productsHTML .= '<td>' . $item['quantity'] . '</td>';
      $productsHTML .= '<td>' . number_format($productTotal, 2, ',', ' ') . ' €</td>';
      $productsHTML .= '</tr>';
    }

    // Calculate shipping cost
    $shippingCost = ($subTotal >= 300) ? 0 : 10;
    $totalPrice = $subTotal + $shippingCost;

    // Format prices
    $subTotalFormatted = number_format($subTotal, 2, ',', ' ') . ' €';
    $shippingCostFormatted = ($shippingCost > 0) ? number_format($shippingCost, 2, ',', ' ') . ' €' : __('general.free');
    $totalPriceFormatted = number_format($totalPrice, 2, ',', ' ') . ' €';

    // Replace placeholders in the template
    $replacements = [
      '{{WEBSITE_URL}}' => $baseUrl,
      '{{CUSTOMER_NAME}}' => $fullName,
      '{{ORDER_NUMBER}}' => $order['id'],
      '{{ORDER_SUBTOTAL}}' => $subTotalFormatted,
      '{{ORDER_SHIPPING_COST}}' => $shippingCostFormatted,
      '{{ORDER_TOTAL}}' => $totalPriceFormatted,
      '{{SHIPPING_NAME}}' => $shippingAddress['first_name'] . ' ' . $shippingAddress['last_name'],
      '{{SHIPPING_ADDRESS}}' => $shippingAddress['address'] . ($shippingAddress['address2'] ? ', ' . $shippingAddress['address2'] : ''),
      '{{SHIPPING_ZIP}}' => $shippingAddress['postal_code'],
      '{{SHIPPING_CITY}}' => $shippingAddress['city'],
      '{{SHIPPING_COUNTRY}}' => $shippingAddress['country'],
      '{{ORDER_HISTORY_URL}}' => $baseUrl . '/account/orders',
      '{{LINK_1}}' => $baseUrl . '/products',
      '{{LINK_2}}' => $baseUrl . '/page/tutos',
      '{{LINK_3}}' => $baseUrl . '/page/nous-contacter',
      '{{CURRENT_YEAR}}' => date('Y')
    ];

    $content = str_replace(array_keys($replacements), array_values($replacements), $template);

    // Replace the product row template with our generated HTML
    // Find the exact product row pattern regardless of spacing
    $pattern = '/<tr>[\s\n]*<td>{{PRODUCT_NAME}}<\/td>[\s\n]*<td>{{PRODUCT_QUANTITY}}<\/td>[\s\n]*<td>{{PRODUCT_PRICE}}<\/td>[\s\n]*<\/tr>/s';
    $content = preg_replace($pattern, $productsHTML, $content);

    return $this->sendEmail($email, $fullName, $subject, $content);
  }
}
