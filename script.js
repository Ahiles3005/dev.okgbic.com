$(document).ready(function(){
    createList();
});
const items=[
    {
        message:'Список наших складов',
        title:'Список наших складов'
    },
    {
        message: 'Здравствуйте, приветствую вас',
        title: 'приветствую'
    }
]

function createList(){
    for (let i = 0; i < items.length; i++) {
        document.getElementById("answerList").innerHTML +=
            `<li class="list-group-item"
onclick="frameCommunicationSend({'action': 'send', 'message': '${items[i].message}'})">${items[i].title}</li>`;
    }
}

function frameCommunicationInit()
{
    if (!window.frameCommunication)
    {
        window.frameCommunication = {timeout: {}};
    }
    if(typeof window.postMessage === 'function')
    {
        window.addEventListener('message', function(event){
            var data = {};
            try { data = JSON.parse(event.data); } catch (err){}

            if (data.action == 'init')
            {
                frameCommunication.uniqueLoadId = data.uniqueLoadId;
                frameCommunication.postMessageSource = event.source;
                frameCommunication.postMessageOrigin = event.origin;
            }
        });
    }
}
// функция отправки данных в основное окно
function frameCommunicationSend(data)
{
    data['uniqueLoadId'] = frameCommunication.uniqueLoadId;
    var encodedData = JSON.stringify(data);
    if (!frameCommunication.postMessageOrigin)
    {
        clearTimeout(frameCommunication.timeout[encodedData]);
        frameCommunication.timeout[encodedData] = setTimeout(function(){
            frameCommunicationSend(data);
        }, 10);
        return true;
    }

    if(typeof window.postMessage === 'function')
    {
        if(frameCommunication.postMessageSource)
        {
            frameCommunication.postMessageSource.postMessage(
                encodedData,
                frameCommunication.postMessageOrigin
            );
        }
    }
}
frameCommunicationInit();