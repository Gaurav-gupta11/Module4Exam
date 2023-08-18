 <?php

// namespace Drupal\author_form\Form;

// use Drupal\Core\Form\FormBase;
// use Drupal\Core\Form\FormStateInterface;
// use Drupal\Core\Session\AccountProxyInterface;
// use Drupal\Core\TempStore\PrivateTempStoreFactory;
// use Symfony\Component\DependencyInjection\ContainerInterface;

// /**
//  * Provides the OTP Verification Form.
//  */
// class OtpVerificationForm extends FormBase {

//   /**
//    * The private tempstore service.
//    *
//    * @var \Drupal\Core\TempStore\PrivateTempStore
//    */
//   protected $privateTempStore;

//   /**
//    * The current user service.
//    *
//    * @var \Drupal\Core\Session\AccountProxyInterface
//    */
//   protected $currentUser;

//   /**
//    * Constructs an OtpVerificationForm object.
//    */
//   public function __construct(PrivateTempStoreFactory $tempStoreFactory, AccountProxyInterface $current_user) {
//     $this->privateTempStore = $tempStoreFactory->get('author_form');
//     $this->currentUser = $current_user;
//   }

//   /**
//    * {@inheritdoc}
//    */
//   public static function create(ContainerInterface $container) {
//     return new static(
//       $container->get('tempstore.private'),
//       $container->get('current_user')
//     );
//   }

//   /**
//    * {@inheritdoc}
//    */
//   public function getFormId() {
//     return 'otp_verification_form';
//   }

//   /**
//    * {@inheritdoc}
//    */
//   public function buildForm(array $form, FormStateInterface $form_state) {
//     $form['otp'] = [
//       '#type' => 'textfield',
//       '#title' => $this->t('Enter OTP'),
//       '#required' => TRUE,
//     ];

//     $form['submit'] = [
//       '#type' => 'submit',
//       '#value' => $this->t('Verify OTP'),
//     ];

//     return $form;
//   }

//   /**
//    * {@inheritdoc}
//    */
//   public function submitForm(array &$form, FormStateInterface $form_state) {
//     $values = $form_state->getValues();
//     $email = $this->currentUser->getEmail();

//     // Retrieve stored OTP from the session
//     $storedOtp = $this->getStoredOtpForValidation($email);

//     // Get entered OTP
//     $enteredOtp = $values['otp'];

//     // Validate OTP
//     if ($enteredOtp !== $storedOtp) {
//       $form_state->setErrorByName('otp', $this->t('Invalid OTP.'));
//       return;
//     }

//     // Clear stored OTP
//     $this->clearStoredOtpForValidation($email);

//     // Create the user (same as in AuthorForm)
//     // ...

//     // Redirect to a thank-you page or another appropriate destination
//     $form_state->setRedirect('user.page');
//   }

//   /**
//    * Stores the OTP value in the session.
//    */
//   protected function storeOtpForValidation($email, $otpValue) {
//     $this->privateTempStore->set($email, $otpValue);
//   }

//   /**
//    * Retrieves the stored OTP value from the session.
//    */
//   protected function getStoredOtpForValidation($email) {
//     return $this->privateTempStore->get($email);
//   }

//   /**
//    * Clears the stored OTP value from the session.
//    */
//   protected function clearStoredOtpForValidation($email) {
//     $this->privateTempStore->delete($email);
//   }
// } 
