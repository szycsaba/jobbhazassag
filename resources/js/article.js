
//divek fade in

document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.fade-section')

    elements.forEach((element,index) => {
        setTimeout(() => {
            element.classList.remove('opacity-0', 'translate-y-5')
        }, index * 100);

    })

})




//menu

const menuBurger = document.querySelector('#menuBurger')
const navMenu = document.querySelector('.navMenu')
const backArrow = document.querySelector('#backArrow')

let isOpen = false

console.log(isOpen)

menuBurger.addEventListener('click',(event)=>{
    if(!isOpen){
        console.log('klikk')

        navMenu.classList.remove('translate-x-full')
        isOpen = true

    }


})

backArrow.addEventListener('click',(event)=>{
    if(isOpen){

        navMenu.classList.add('translate-x-full')
        isOpen = false

    }


})

//lenyíló inputok

/*
const inputArrows = document.querySelectorAll('.dropdownButton')
const textAreas = document.querySelectorAll('.inpArea')
const arrowIcons = document.querySelectorAll('.arrowIcon')
 
inputArrows.forEach((btn,indexBtn) => {

    
    btn.addEventListener('click',()=>{
        console.log('klikk')

        textAreas.forEach((textArea,indexTxt) => {
            if(indexBtn == indexTxt){
                textArea.classList.toggle('hidden')


            }

            
        });

    })
    
})
*/





