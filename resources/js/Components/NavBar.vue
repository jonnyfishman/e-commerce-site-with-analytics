<template>
  <nav>
    <div class="nav-header">
      <div class="langs">
        <select id="languages">
          <option value="EN">EN / £</option>
          <option value="DE">DE / €</option>
          <option value="FR">FR / €</option>
        </select>
      </div>
      <div class="branding">
        <h1>Reco<span>Shoes</span></h1>
      </div>
      <div class="interactions">
        <fieldset>
          <input type="search" placeholder="Search" id="search" size="16"/>
          <button><font-awesome-icon icon="fa-solid fa-magnifying-glass" /></button>
        </fieldset>
        <a href="Basket" alt="Basket"><font-awesome-icon icon="fa-solid fa-basket-shopping" /></a>
        <a href="Account" alt="Account"><font-awesome-icon icon="fa-solid fa-user" /></a>
        <a href="Recommend" alt="Recommend"><font-awesome-icon icon="fa-solid fa-heart" /></a>
      </div>
    </div>
    <ul>
      <li><a>Mens</a></li>
      <li><a>Womens</a></li>
    </ul>
  </nav>
</template>

<script>
export default {
  name: 'NavigationBar'
}
</script>

<style scoped>
  nav {
    position:sticky;
    box-shadow: 0 0 10px rgba(33,33,33,0.5);
    clip-path: inset(0px 0 -10px 0px);
    width:100%;
    top:0;
    z-index:100;
    background:white;
  }
  nav .nav-header {
    display:flex;
    flex-direction:row;
    justify-content:space-around;
    padding:0 10vw;
  }
  nav .nav-header div {
    width:33%;
    display:flex;
    align-items:center;
  }
  nav .nav-header div:first-child {
    justify-content:left;
  }
  nav .nav-header .branding {
    width:20%;
    justify-content:center;
  }
  nav .nav-header div:last-child {
    justify-content:right;
  }

  nav h1 {
    font-size:2em;
    margin:0.2em;
  }
  nav h1 span {
    font-size:1.4em;
    color:#ccc;
  }
  nav select {
    border: 1px solid black;
    background: white;
    padding: 0.4em;
    border-radius: 9999px;
  }
  nav input[type=search]:placeholder {
    text-align:right;
  }
  nav .interactions fieldset {
    padding: 0;
    border-radius: 1em;
    border: 1px solid #222;
    overflow:hidden;
  }
  nav input[type=search], nav button {
    padding:0.4em;
    background: transparent;
    text-align:right;
    border:none;
    color:#ccc;
  }
  nav input[type=search] {
    padding:0.4em 0 0.4em 0.4em;
  }
  nav button {
    cursor:pointer;
    background-size: 200% 100%;
    background-image: linear-gradient(to right, transparent 50%, #222 50%);
    -webkit-transition: background-position 1s;
    -moz-transition: background-position 1s;
    transition: background-position 0.05s;
  }
  nav button:hover {
    background-position: 100% 0;
    color:#fff;
  }
  input:-moz-placeholder, input:-ms-input-placeholder, input::-webkit-input-placeholder{
    color:#ccc;
  }
  nav .interactions a {
    margin: 0 0.4em;
    color:inherit;
  }

  nav ul {
    list-style: none;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    padding: 0;
    margin: auto;
    width: 50%;
    justify-content:center;
  }
  nav ul li {
    flex: 0 1 auto;
    display: inline-flex;

    justify-content: center;
  }
  nav ul li a {

    margin: 0.6em;
    position:relative;
    text-align:center;
  }
  nav ul li > a:after {
    content: '';
    width: 0;
    transition: 0.2s;
    height: 2px;
    background: #222;
    display: inline-block;
    position: absolute;
    left: 50%;
    bottom: -0.6em;
  }
  nav ul li:hover > a:after {
    left:-0.6em;
    width:calc(100% + 1.2em);
  }
</style>
