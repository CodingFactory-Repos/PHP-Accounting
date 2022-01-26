<?php
require APP_ROOT . '/views/inc/head.php';
?>

<?php
require APP_ROOT . '/views/inc/nav.php';
?>

<main>
    <h2>Register</h2>

    <form
        id="register-form"
        method="POST"
        action="<?php echo URL_ROOT; ?>/users/register"
    >
        <input type="text" placeholder="lastname *" name="lastname">
        <span class="invalidFeedback">
            <?php echo $data['lastnameError']; ?>
        </span>

        <input type="email" placeholder="Email *" name="email">
        <span class="invalidFeedback">
            <?php echo $data['emailError']; ?>
        </span>

        <input type="password" placeholder="Password *" name="password">
        <span class="invalidFeedback">
            <?php echo $data['passwordError']; ?>
        </span>

        <input type="password" placeholder="Confirm Password *" name="confirmPassword">
        <span class="invalidFeedback">
            <?php echo $data['confirmPasswordError']; ?>
        </span>

        <button id="submit" type="submit" value="submit">Submit</button>

        <p class="options">Already register ? <a id="linklogin" href="<?php echo URL_ROOT; ?>/users/login">Login to your account</a></p>
    </form>
</main>