"use strict";
document.addEventListener("DOMContentLoaded", function () {
  const usernameInput = document.getElementById("username");
  const xhr = new XMLHttpRequest();

  usernameInput.addEventListener("blur", (event) => {
      const errorExists = document.getElementById("username_error");
      if (errorExists) {
          errorExists.remove();
      }

      const usernameValue = usernameInput.value.trim(); // Trim to remove leading/trailing spaces

      if (usernameValue !== "") {
          xhr.open("GET", `checkusername.php?username=${encodeURIComponent(usernameValue)}`);
          xhr.addEventListener("load", (ev) => {
              if (xhr.status === 200) {
                  if (xhr.responseText === "error" || xhr.responseText === "true") {
                      const errorSpan = document.createElement("span");
                      errorSpan.classList.add("error");
                      errorSpan.id = "username_error";
                      errorSpan.innerText = "Username already exists. Please choose a different one.";
                      usernameInput.insertAdjacentElement('afterend', errorSpan);
                  }
              } else {
                  console.error("Connection Failed");
              }
          });
          xhr.send();
      }
  });

  const clickPassword = document.getElementById("clickPassword");
  const passwordInput = document.getElementById("password");

  clickPassword.addEventListener("click", (ev) => {
      passwordInput.type = (passwordInput.type === "password") ? "text" : "password";
  });

  const descInput = document.getElementById("list_desc");
  const count = document.getElementById("charCounter");
  const maxChars = 2500;

  descInput.addEventListener("input", function () {
      const remainingChars = maxChars - descInput.value.length;
      count.innerText = `${remainingChars} characters remaining`;

      if (remainingChars < 50) {
          count.style.color = "red";
      } else {
          count.style.color = "blue";
      }
  });
});
