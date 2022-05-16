<?php include 'header.php';
?>
<header class="main">
    <h1>Chatroom</h1>
</header>
<div class="table-wrapper">
    <div align="center">
        <h2>Join our Discord!</h2>
        <iframe src="https://discordapp.com/widget?id=361689774723170304&theme=dark&username=<?php echo $_SESSION['loggedin'] ? $_SESSION['profile']['username'] : ''; ?>"
                width="350" height="500" allowtransparency="true" frameborder="0"></iframe>
    </div>
    <table cellspacing="0" cellpadding="10">
        <tr>
            <td id="messageBox" width="100%" style="overflow-y:scroll;height:500px;display:block;">
                <table id="messages" class="alt"></table>
            </td>
            <td width="5%" valign="top" style="overflow-y:scroll;overflow-x:scroll;">
                <h2>Online</h2>
                <table id="userlist" class="alt"><i>Disabled</i></table>
            </td>
        </tr>
        <tr>
            <td>
                <?php if ($_SESSION['loggedin']) {
                    echo '<textarea id="entry" placeholder="Message..." maxlength="255"></textarea>';
                } else {
                    echo '<textarea id="entry" placeholder="Requires Login" maxlength="255" disabled="true"></textarea>';
                } ?>
            </td>
        </tr>
    </table>
</div>
<script src="/assets/js/jquery.min.js"></script>
<script type="text/javascript">
    var messageTable = document.getElementById('messages');
    var userTable = document.getElementById('userlist');
    var last_message_time = 0;
    var scroll = true;
    var max_polling = 20;
    var polling = 1;
    update();

    function addNewMessage(username, message, time) {
        var newRow = messageTable.insertRow(messageTable.rows.length).insertCell(0);
        var timeData = '<div style="text-align:right;vertical-align:top;font-size:9px;color:grey;">' + time + '</div>';
        newRow.innerHTML = timeData + '<strong>' + username + ': </strong>' + message;
    }

    function update() {
        $.ajax({
            type: 'POST',
            url: 'scripts/updateChatroom.php',
            dataType: 'json',
            data: {'last_message_time': last_message_time},
            success: function (data) {
                messages = data.messages;
                users = data.users;
                if (messages.length) {
                    polling = 1;
                    $.each(messages, function (i, message) {
                        addNewMessage(message.username, message.message, message.time);
                        last_message_time = message.time;
                    });
                    if (scroll || $('#messageBox').scrollTop() > $('#messages').height() * 0.75) {
                        $('#messageBox').animate({scrollTop: $('#messages').height()}, 1000);
                        scroll = false;
                    }
                } else {
                    if (polling < max_polling) polling = polling + 1;
                }
                if (users.length) {
                    userTable.innerHTML = "";
                    $.each(users, function (i, user) {
                        var row = userTable.insertRow(userTable.rows.length);
                        row.innerHTML = user;
                    });
                    if (users.length > 1 && polling > 5) {
                        polling = 5;

                    }
                }
                setTimeout(update, polling * 1000);
            }
        });
    }

    function sendMessage(message) {
        $.post('scripts/sendChatroom.php', {'message': message},
            function (data) {
                scroll = true;
            }
        );
    }

    $(function () {
        $("#entry").keyup(function (event) {
            if (event.which == 13) {
                event.preventDefault();
                var msg = $(this).val().trim();
                $(this).val('');
                if (msg != '') {
                    sendMessage(msg);
                }
                return false;
            }
        });
    });
</script>
<?php include 'navi-footer.php'; ?>
