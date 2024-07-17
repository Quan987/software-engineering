// Login function
function login() {
  // Placeholder for login functionality
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;

  // Clear previous errors
  document.getElementById("email_err").innerText = "";
  document.getElementById("pass_err").innerText = "";

  // Basic validation
  let isValid = true;
  if (!email) {
    document.getElementById("email_err").innerText = "Email is required";
    isValid = false;
  }
  if (!password) {
    document.getElementById("pass_err").innerText = "Password is required";
    isValid = false;
  }

  if (isValid) {
    // Prepare data to send
    const formData = new FormData();
    formData.append("email", email);
    formData.append("password", password);

    // Send login request to server
    fetch("login.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json()) // Assuming login.php returns JSON
      .then((data) => {
        // Handle response from server
        if (data.success) {
          alert("Login successful!");
          window.location.href = "index.html"; // Redirect to dashboard page
        } else {
          alert("Invalid email or password.");
        }
      })
      .catch((error) => {
        console.error("Error:", error);
        alert("Error occurred during login. Please try again.");
      });
  }
}
