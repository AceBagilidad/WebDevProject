  // Function to toggle between sign-in and sign-up forms
  function toggleForms() {
      // Get elements for sign-in form, sign-up form, and their headers
      const signInForm = document.getElementById('sign-in-form');
      const signUpForm = document.getElementById('sign-up-form');
      const signInHeader = document.getElementById('sign-in-header');
      const signUpHeader = document.getElementById('sign-up-header');

      // Get elements for toggle buttons
      const toggleToSignIn = document.getElementById('toggle-to-sign-in'); // Button to switch to sign-in form
      const toggleToSignUp = document.querySelector('.toggle'); // Button to switch to sign-up form

      // Check if the sign-in form is currently visible
      if (signInForm.style.display !== 'none') {
          // Hide the sign-in form and show the sign-up form
          signInForm.style.display = 'none';
          signUpForm.style.display = 'block';

          // Hide the sign-in header and show the sign-up header
          signInHeader.style.display = 'none';
          signUpHeader.style.display = 'block';

          // Show the toggle button for sign-in and hide the one for sign-up
          toggleToSignIn.style.display = 'block';
          toggleToSignUp.style.display = 'none';
      } else {
          // Show the sign-in form and hide the sign-up form
          signInForm.style.display = 'block';
          signUpForm.style.display = 'none';

          // Show the sign-in header and hide the sign-up header
          signInHeader.style.display = 'block';
          signUpHeader.style.display = 'none';

          // Hide the toggle button for sign-in and show the one for sign-up
          toggleToSignIn.style.display = 'none';
          toggleToSignUp.style.display = 'block';
      }
  }