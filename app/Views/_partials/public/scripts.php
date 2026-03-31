<!-- Cursor -->
<script>
const cursor=document.getElementById('cursor'),ring=document.getElementById('cursorRing');
let mx=0,my=0,rx=0,ry=0;
document.addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY});
(function loop(){
  if(cursor){cursor.style.left=mx+'px';cursor.style.top=my+'px'}
  rx+=(mx-rx)*.13;ry+=(my-ry)*.13;
  if(ring){ring.style.left=rx+'px';ring.style.top=ry+'px'}
  requestAnimationFrame(loop)
})();
document.querySelectorAll('a,button').forEach(el=>{
  el.addEventListener('mouseenter',()=>ring&&(ring.style.transform='translate(-50%,-50%) scale(1.7)'));
  el.addEventListener('mouseleave',()=>ring&&(ring.style.transform='translate(-50%,-50%) scale(1)'));
});

// Burger
const burger=document.getElementById('burger'),drawer=document.getElementById('drawer');
if(burger&&drawer){
  burger.addEventListener('click',()=>{
    burger.classList.toggle('open');
    drawer.classList.toggle('open');
  });
  drawer.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{
    burger.classList.remove('open');
    drawer.classList.remove('open');
  }));
}

// Scroll reveal
const obs=new IntersectionObserver(entries=>{
  entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('in');obs.unobserve(e.target)}});
},{threshold:.1});
document.querySelectorAll('.reveal').forEach(el=>obs.observe(el));
</script>
