function addTeam() {
    var new_team_number = $('#contestantsDiv > div').length + 1;
    var team_copy = $('#contestantsDiv div:first').clone()[0];
    team_copy.getElementsByTagName('label')[0].innerText = "Team " + new_team_number;
    var member_selects_copy = team_copy.getElementsByTagName('select');
    for (i = 0; i < member_selects_copy.length; i++) {
        member_selects_copy[i].selectedIndex = false;
        if (i == 0) {
            member_selects_copy[i].setAttribute('name', "contestants[" + new_team_number + "][multiplier]");
        } else {
            member_selects_copy[i].setAttribute('team', new_team_number);
            member_selects_copy[i].setAttribute('name', 'contestants[' + new_team_number + '][]');
        }
    }
    $('#contestantsDiv')[0].appendChild(team_copy);
}

function addMembers() {
    $('#contestantsDiv div > .form-row').each(function () {
        var team_member_clone = $(this).find('select:first').parent().clone();
        team_member_clone.find(':selected').removeAttr('selected');
        $(this).append(team_member_clone);
    });
}
