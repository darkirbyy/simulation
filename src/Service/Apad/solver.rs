use std::str::FromStr;

// ////////////////////////////////
// Input conversion
// ////////////////////////////////

#[derive(Debug)]
enum Month { 
    Jan = 0, Feb = 1, Mar = 2, Apr = 3, May = 4, Jun = 5, Jul = 7, Aug = 8, Sep = 9, Oct = 10, Nov = 11, Dec = 12,
}

impl FromStr for Month {
    type Err = ();

    fn from_str(input: &str) -> Result<Month, Self::Err> {
        match input {
            "jan" => Ok(Month::Jan),
            "feb" => Ok(Month::Feb),
            "mar" => Ok(Month::Mar),
            "apr" => Ok(Month::Apr),
            "may" => Ok(Month::May),
            "jun" => Ok(Month::Jun),
            "jul" => Ok(Month::Jul),
            "aug" => Ok(Month::Aug),
            "sep" => Ok(Month::Sep),
            "oct" => Ok(Month::Oct),
            "nov" => Ok(Month::Nov),
            "dec" => Ok(Month::Dec),
            _ => Err(()),
        }
    }
}

// ////////////////////////////////
// Coordinate system
// ////////////////////////////////

#[derive(Debug, Default, Clone, PartialEq, Eq, PartialOrd, Ord)]
struct Co {
    row: i8,
    col: i8,
}

impl Co {
    fn from_i8(number: i8) -> Co {
        Co {row : number / 7, col : number % 7}
    }
}

// ////////////////////////////////
// Pose operation
// ////////////////////////////////

#[derive(Debug, Default, Clone, PartialEq, Eq)]
struct Pose {
    cells: Vec<Co>,
}

impl Pose {
    fn standardize(&mut self) {
        self.cells.sort();
        let offset = self.cells[0].clone();
        for cell in self.cells.iter_mut() {
            cell.row -= offset.row;
            cell.col -= offset.col;
        }
    }

    fn flip(&mut self) {
        for cell in self.cells.iter_mut() {
            cell.col = -cell.col;
        }
    }

    fn rotate90(&mut self) {
        for cell in self.cells.iter_mut() {
            let temp = cell.row;
            cell.row = -cell.col;
            cell.col = temp;
        }
    }
}

// ////////////////////////////////
// Piece operations
// ////////////////////////////////

#[derive(Debug)]
struct Piece {
    poses: Vec<Pose>,
}

impl Piece {
    fn add_pose(&mut self, new_pose: &Pose) {
        for pose in self.poses.iter() {
            if *pose == *new_pose {
                return;
            }
        }
        self.poses.push(new_pose.clone());
    }

    fn new(pose: &mut Pose) -> Self {
        let mut piece = Piece { poses: vec![] };

        for k in 0..8 {
            if k == 4 {
                pose.flip();
            }
            if k % 4 != 0 {
                pose.rotate90();
            }
            pose.standardize();
            piece.add_pose(pose);
        }

        piece
    }
}

// ////////////////////////////////
// Piece set
// ////////////////////////////////

const MAX_PIECES: usize = 8;

#[derive(Debug)]
struct PieceSet {
    pieces: [Piece; MAX_PIECES],
    remaining: [bool; MAX_PIECES],
}

impl PieceSet {
    fn new() -> PieceSet {
        PieceSet {
            #[rustfmt::skip]
            pieces: [
                Piece::new(&mut Pose { cells: vec![Co {row: 0, col: 0}, Co {row: 1, col: 0}, Co {row: 1, col: 1}, Co {row: 1, col: 2}, Co {row: 2, col: 2}]}),
                Piece::new(&mut Pose { cells: vec![Co {row: 0, col: 0}, Co {row: 0, col: 1}, Co {row: 0, col: 2}, Co {row: 1, col: 0}, Co {row: 2, col: 0}]}),
                Piece::new(&mut Pose { cells: vec![Co {row: 0, col: 0}, Co {row: 0, col: 1}, Co {row: 0, col: 2}, Co {row: 1, col: 2}, Co {row: 1, col: 3}]}),
                Piece::new(&mut Pose { cells: vec![Co {row: 0, col: 0}, Co {row: 0, col: 1}, Co {row: 0, col: 2}, Co {row: 0, col: 3}, Co {row: 1, col: 0}]}),
                Piece::new(&mut Pose { cells: vec![Co {row: 0, col: 0}, Co {row: 0, col: 1}, Co {row: 0, col: 2}, Co {row: 0, col: 3}, Co {row: 1, col: 1}]}),
                Piece::new(&mut Pose { cells: vec![Co {row: 0, col: 0}, Co {row: 0, col: 1}, Co {row: 0, col: 2}, Co {row: 1, col: 0}, Co {row: 1, col: 1}]}),
                Piece::new(&mut Pose { cells: vec![Co {row: 0, col: 0}, Co {row: 0, col: 1}, Co {row: 0, col: 2}, Co {row: 1, col: 0}, Co {row: 1, col: 2}]}),
                Piece::new(&mut Pose { cells: vec![Co {row: 0, col: 0}, Co {row: 0, col: 1}, Co {row: 0, col: 2}, Co {row: 1, col: 0}, Co {row: 1, col: 1}, Co {row: 1, col:2}]}),
            ],
            remaining: [true; 8],
        }
    }
}

// ////////////////////////////////
// Grid
// ////////////////////////////////

const GRID_HEIGHT: usize = 7;
const GRID_WIDTH: usize = 7;
struct Grid {
    cells: [[i8; GRID_WIDTH]; GRID_HEIGHT],
}

impl Grid {
    #[rustfmt::skip]
    fn new(Co {row: row1, col: col1,}: Co, Co {row: row2, col: col2,}: Co) -> Grid {
        let mut grid = Grid {
            cells: [[0; GRID_WIDTH]; GRID_HEIGHT],
        };
        grid.cells[0][6] = 10;
        grid.cells[1][6] = 10;
        grid.cells[6][3] = 10;
        grid.cells[6][4] = 10;
        grid.cells[6][5] = 10;
        grid.cells[6][6] = 10;
        grid.cells[row1 as usize][col1 as usize] = 9;
        grid.cells[row2 as usize][col2 as usize] = 9;
        grid
    }

    fn find_first_free_cell(&self) -> Option<Co> {
        for i in 0..GRID_HEIGHT {
            for j in 0..GRID_WIDTH {
                if self.cells[i][j] == 0 {
                    return Option::Some(Co {
                        row: i as i8,
                        col: j as i8,
                    });
                }
            }
        }
        Option::None
    }

    fn is_pose_valid(&self, pose: &Pose, co: &Co) -> bool {
        for cell in pose.cells.iter() {
            let ii = (co.row + cell.row) as usize;
            let jj = (co.col + cell.col) as usize;
            if ii >= GRID_HEIGHT || jj >= GRID_WIDTH || self.cells[ii][jj] != 0 {
                return false;
            }
        }
        return true;
    }

    fn pose(&mut self, pose: &Pose, co: &Co, value: i8) {
        for cell in pose.cells.iter() {
            let ii = (co.row + cell.row) as usize;
            let jj = (co.col + cell.col) as usize;
            self.cells[ii][jj] = value;
        }
    }

    fn print(&self) {
        for i in 0..GRID_HEIGHT {
            for j in 0..GRID_WIDTH {
                print!("{}", self.cells[i][j]);
                if i < GRID_HEIGHT - 1 || j < GRID_WIDTH - 1 {
                    print!(";");
                }
            }
        }
        println!();
    }
}

// ////////////////////////////////
// Solver
// ////////////////////////////////

fn solve(grid: &mut Grid, piece_set: &mut PieceSet) {
    let co = grid.find_first_free_cell();
    match co {
        None => {
            grid.print();
        }
        Some(co) => {
            for k in 0..piece_set.pieces.len() {
                if piece_set.remaining[k] == false {
                    continue;
                }
                for l in 0..piece_set.pieces[k].poses.len() {
                    if grid.is_pose_valid(&piece_set.pieces[k].poses[l], &co) {
                        grid.pose(&piece_set.pieces[k].poses[l], &co, (k + 1) as i8);
                        piece_set.remaining[k] = false;

                        solve(grid, piece_set);

                        grid.pose(&piece_set.pieces[k].poses[l], &co, 0);
                        piece_set.remaining[k] = true;
                    }
                }
            }
        }
    }
}

fn print_solutions(day: Co, month: Co) {
    let mut piece_set = PieceSet::new();
    let mut grid = Grid::new(day, month);
    solve(&mut grid, &mut piece_set);
}

fn main() {
    let day: i8 = std::env::args().nth(1).expect("no day given").trim().parse().expect("unable to parse the day");
    let month: i8 = std::env::args().nth(2).expect("no month given").trim().parse::<Month>().expect("Unable to parse the month") as i8;

    print_solutions(Co::from_i8(day + 13), Co::from_i8(month));
}
