<!-- Cursor -->
<script>
const cursor=document.getElementById('cursor'),ring=document.getElementById('cursorRing');
document.addEventListener('mousemove',e=>{
  const x=e.clientX,y=e.clientY;
  if(cursor){cursor.style.left=x+'px';cursor.style.top=y+'px'}
  if(ring){ring.style.left=x+'px';ring.style.top=y+'px'}
});
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
