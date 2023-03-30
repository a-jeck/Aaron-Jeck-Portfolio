This Python script is designed to analyze baseball game statistics for a full season. The program accepts input text files containing the data for each game, and parses each line using a regular expression to extract relevant information about player performance.

Using this data, the script then calculates the batting average for each player over the course of the season, rounding the result to the nearest hundredth. Finally, the program generates a sorted list of the players based on their batting averages, with the highest averages at the top and the lowest at the bottom. The text file must be formatted in this template:

=== [Info about game] === <br>
[Player] batted [x] times with [y] hits and [z] runs <br>
More players and more games...
