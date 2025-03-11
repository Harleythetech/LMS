function realtime() {
  
    let time = moment().format('h:mm:ss a');
    document.getElementById('time').innerHTML = time;
    
    setInterval(() => {
      time = moment().format('h:mm:ss a');
      document.getElementById('time').innerHTML = time;
    }, 1000)
  }
  
  realtime();



  document.querySelector("form").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission
    let searchValue = document.querySelector("input[name='search']").value;

    fetch(`app/overview.php?search=${encodeURIComponent(searchValue)}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById("search-results").innerHTML = data;
        });
});


