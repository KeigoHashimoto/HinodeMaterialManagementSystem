// 取消しボタンが右脇に消えていく
const backBtn = document.getElementById('back-btn');

setTimeout(function(){
    backBtn.classList.add('back-remove');
},8000);


//削除時に確認の表示
const deleteBtn = document.querySelectorAll('.delete');

for(i=0;i<deleteBtn.length;i++){
    deleteBtn[i].addEventListener('click',(e)=>{
        if(confirm('本当に削除しますか？')){
            return true;
        }else{
            alert('削除を中止しました。');
            e.preventDefault();
        }
    })
}

//ログアウト時に確認

const logout = document.getElementById('logout');

logout.addEventListener('click',(e)=>{
    if(confirm('ログアウトしますか？')){
        return true;
    }else{
        e.preventDefault();
    }
})
