<?php
namespace Drupal\author_form\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\user\Entity\User;
use Drupal\Core\Mail\MailManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
/**
 * Provides the Author Form.
 */
class AuthorForm extends FormBase {
  /**
   * The mail manager service.
   *
   * @var \Drupal\Core\Mail\MailManagerInterface
   */
  protected $mailManager;
  /**
   * Constructs an AuthorForm object.
   *
   * @param \Drupal\Core\Mail\MailManagerInterface $mail_manager
   *   The mail manager service.
   */
  public function __construct(MailManagerInterface $mail_manager) {
    $this->mailManager = $mail_manager;
  }
  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.mail')
    );
  }
  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'author_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    // Define form fields
    $form['full_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Full Name'),
      '#required' => TRUE,
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email Address'),
      '#required' => TRUE,
    ];
    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#required' => TRUE,
    ];
    $form['role'] = [
      '#type' => 'radios',
      '#title' => $this->t('Role'),
      '#options' => [
        'bloggers' => $this->t('Blogger'),
        'guestbloggers' => $this->t('Guest Blogger'),
      ],
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state)
  {
    // Validate email address.
    $email = $form_state->getValue('email');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $form_state->setErrorByName('email', $this->t('Invalid email address.'));
    }
    // Validate role selection.
    $role = $form_state->getValue('role');
    if (!in_array($role, ['bloggers', 'guestbloggers'])) {
      $form_state->setErrorByName('role', $this->t('Invalid role selection.'));
    }
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    // Get form values
    $values = $form_state->getValues();
    // Create new user
    $user = User::create();
    $user->setPassword($values['password']);
    $user->enforceIsNew();
    $user->setEmail($values['email']);
    $user->setUsername($values['full_name']);
    $user->addRole($values['role']);
    $user->block();
    $user->save();
   // Send email notification to admin
  $params = [
    'subject' => 'New user registration',
    'body' => 'A new user has submitted the registration form. Please review and approve.',
  ];
  $this->mailManager->mail('author_form', 'notification', 'gaurav.gupta@innoraft.com', LanguageInterface::LANGCODE_DEFAULT, $params, NULL, TRUE);
  // Send thank you email to the user
  $params = [
    'subject' => 'Thank you for your submission',
    'body' => 'Thank you for submitting the registration form. We will get back to you soon.',
  ];
  $this->mailManager->mail('author_form', 'notification', $values['email'], LanguageInterface::LANGCODE_DEFAULT, $params, NULL, TRUE);
  }
}