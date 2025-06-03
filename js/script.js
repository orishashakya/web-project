let searchForm = document.querySelector('.search-form');

document.querySelector('#search-btn').onclick = () =>
{
    searchForm.classList.toggle('active');
    navbar.classList.remove('active');
}



let navbar = document.querySelector('.navbar');

document.querySelector('#menu-btn').onclick = () =>
{
    navbar.classList.toggle('active');
    searchForm.classList.remove('active');
}

window.onscroll = () =>
    {
        searchForm.classList.remove('active');
        navbar.classList.remove('active');
    }


/*slide*/
    let slider = document.querySelector('.slider'); 
        let scrollAmount = slider.clientWidth; 
        document.querySelector("#nextbtn").addEventListener("click", () => { 
            slider.scrollLeft += scrollAmount;      }); 
        document.querySelector("#backbtn").addEventListener("click", () => { 
            slider.scrollLeft -= scrollAmount;      });





