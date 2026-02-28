[View Documentation](https://docs.wilds.mhdb.io) | [Join us on Discord](https://discord.gg/6GEHHQh)

# About
This is the source code for the Monster Hunter Wilds database project, forked from
[mhdb-core](https://github.com/LartTyler/mhdb-core). This repository does not contain any actual data; if you're looking
for the sources used to populate the API, they can be found
[here](https://github.com/LartTyler/mhdb-wilds-data/tree/main/output/merged).

API usage documentation can be found at [https://docs.wilds.mhdb.io](https://docs.wilds.mhdb.io).

**Please note** that this API is a work in progress. Data is sourced from the game's files, and it takes time to
build the systems required to import that information into the API. The
[mhdb-wilds-data](https://github.com/LartTyler/mhdb-wilds-data) project contains the toolset used to extract and merge
the raw data into "friendly" files, which are then imported by the
[importer suite](https://github.com/LartTyler/mhdb-wilds/tree/main/src/Import) in this project. If you'd like to
contribute, feel free to open a pull request here, or reach out in our [Discord server](https://discord.gg/6GEHHQh).
