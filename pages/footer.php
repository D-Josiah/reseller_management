<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

footer {
  height: 10vh;
  background-color: black;
  color: white;
  display: flex;
  justify-content: center;
  gap: 70px;
  position: fixed;
  width: 100%;
}

.companyWrapper,
.supportWrapper,
.creditsWrapper {
  display: flex;
  align-items: center;
  gap: 15px;
  font-size: small;
}

.companyWrapper img,
.supportWrapper img,
.creditsWrapper img {
  width: 30px;
}

.website {
  text-decoration: none;
  color: white;
}

</style> 

<footer>
  <!--insert in all pages using php-->

  <div class="companyWrapper">
    <img src="../images/logo.png" />
    <div class="company">
      <p>AQUAFALSK WEBSITE</p>
      <a href="www.aquaflask.com" class="website">www.aquaflask.com </a>
    </div>
  </div>

  <div class="supportWrapper">
    <img src="../images/support.png" />
    <div class="support">
      <p>Developer Support</p>
      <p>rmssupport@gmail.com</p>
    </div>
  </div>

  <div class="creditsWrapper">
    <img src="../images/copyright.png" />
    <div class="credits">
      <p>2024 Reseller Management Sytem</p>
      <p>All rights reserved.</p>
    </div>
  </div>
</footer>

