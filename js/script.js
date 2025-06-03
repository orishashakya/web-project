let searchForm = document.querySelector('.search-form');

document.querySelector('#search-btn').onclick = () =>
{
    searchForm.classList.toggle('active');

    navbar.classList.remove('active');
    loginForm.classList.remove('active');
    //Removing Other Bars
}



let navbar = document.querySelector('.navbar');

document.querySelector('#menu-btn').onclick = () =>
{
    navbar.classList.toggle('active');

    searchForm.classList.remove('active');
    loginForm.classList.remove('active');
}
//Displaying the User Login Form
let loginForm = document.querySelector('.login-form');

document.querySelector('#login-btn').onclick = () =>
{
    loginForm.classList.toggle('active');

    navbar.classList.remove('active');
    searchForm.classList.remove('active');
    
}

//Removing Side Bars When Scrolling the Window
window.onscroll = () => {
    navbar.classList.remove('active');
    searchForm.classList.remove('active');
    loginForm.classList.remove('active');
  
}




    


/*slide*/
    let slider = document.querySelector('.slider'); 
        let scrollAmount = slider.clientWidth; 
        document.querySelector("#nextbtn").addEventListener("click", () => { 
            slider.scrollLeft += scrollAmount;      }); 
        document.querySelector("#backbtn").addEventListener("click", () => { 
            slider.scrollLeft -= scrollAmount;      });





