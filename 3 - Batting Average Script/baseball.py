import re
import sys


if (len(sys.argv) != 2):
    print("Usage: python3 baseball.py input_txt")
else:
    file_to_read = sys.argv[1]
    player_stats = {}
    player_avg =  {}
    team_stats = open(file_to_read)
    for line in team_stats:
        player_pattern = "^(?P<name>(\w+)\s(\S*))[^\d]+(?P<bats>\d+)[^\d]+(?P<hits>\d+)[^\d]+(?P<runs>\d+)[^\d]+$"

        r1 = re.search(player_pattern, line)
        if r1 is not None:
            name = r1.group('name')
            bats = r1.group('bats')
            hits = r1.group('hits')
    
            if name in player_stats:
                player_stats[name]['bats'] += int(bats)
                player_stats[name]['hits'] += int(hits)
            else:
                player_stats[name] = {'bats':0, 'hits':0, 'runs':0}
                player_stats[name]['bats'] += int(bats)
                player_stats[name]['hits'] += int(hits)

    for player in player_stats:
        name = player
        total_bats = player_stats[name]['bats']
        total_hits = player_stats[name]['hits']
        avg = total_hits / total_bats
        player_avg[name] = avg

    player_averages = list(player_avg.values())
    player_averages.sort(reverse = True)

    player_avg_sorted = sorted(player_avg.items(), key=lambda x: x[1], reverse = True)

    for player in player_avg_sorted:
        name = player[0]
        avg = player[1]
        rounded_avg = round(avg, 3)
    
        if (len(str(rounded_avg)) < 5):
            missing_zeroes = 5 - len(str(rounded_avg))
            zeroes = "0" * missing_zeroes
            rounded_avg = str(rounded_avg) + zeroes
    
        print(name + ": " + str(rounded_avg))
