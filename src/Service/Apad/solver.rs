use std::sync::Mutex;

#[rustfmt::skip]
const ROT: [i8;49] = [-18, -11,  -4,   3,  10,  17,  24, 
                      -19, -12,  -5,   2,   9,  16,  23,
                      -20, -13,  -6,   1,   8,  15,  22, 
                      -21, -14,  -7,   0,   7,  14,  21, 
                      -22, -15,  -8,  -1,   6,  13,  20, 
                      -23, -16,  -9,  -2,   5,  12,  19, 
                      -24, -17, -10,  -3,   4,  11,  18];

#[rustfmt::skip]
const SYM: [i8;49] = [ 18,  19,  20,  21,  22,  23,  24,
                       11,  12,  13,  14,  15,  16,  17,
                        4,   5,   6,   7,   8,   9,  10,
                       -3,  -2,  -1,   0,   1,   2,   3, 
                      -10,  -9,  -8,  -7,  -6,  -5,  -4, 
                      -17, -16, -15, -14, -13, -12, -11, 
                      -24, -23, -22, -21, -20, -19, -18];

#[derive(Clone)]
struct Piece {
    representation: u8,
    placements: Vec<[i8; 6]>,
}

fn main() {
    board_solve(board_init(10,44));
}

fn board_solve(board: [u8; 49]) -> u32 {
    let pieces = pieces_init(board);
    let count: Mutex<u32> = Mutex::new(0);
    board_complete(board, pieces, &count);
    return *count.lock().unwrap();
}

fn board_complete(board: [u8; 49], mut pieces: Vec<Piece>, count: &Mutex<u32>) {
    if pieces.iter().count() == 0 {
        *count.lock().unwrap() += 1;
        board_display_raw(board);
    } else {
        pieces.sort_by(|a, b| {
            b.placements
                .iter()
                .count()
                .cmp(&a.placements.iter().count())
        });
        let piece = pieces.pop().expect("Unable to pop an new piece.");
        for placement in piece.placements.iter() {
            let new_board = board_add_placement(board, *placement, piece.representation);
            let new_pieces = pieces_intersect_placement(&pieces, *placement);
            board_complete(new_board, new_pieces, count);
        }
    }
}

fn board_init(c1: usize, c2: usize) -> [u8; 49] {
    let mut board: [u8; 49] = [0; 49];
    board[6] = 10;
    board[13] = 10;
    board[45] = 10;
    board[46] = 10;
    board[47] = 10;
    board[48] = 10;

    board[c1] = 9;
    board[c2] = 9;
    return board;
}

fn board_add_placement(mut board: [u8; 49], placement: [i8; 6], representation: u8) -> [u8; 49] {
    for cell in placement {
        board[cell as usize] = representation;
    }

    return board;
}

fn board_display_raw(board: [u8; 49]) {
    for (key, cell) in board.iter().enumerate() {
        print!("{}", *cell);
        if key < 48 {
            print!(";");
        }
    }
    println!();
}

#[rustfmt::skip]
fn pieces_init(board: [u8; 49]) -> Vec<Piece> {
    let piece1 = Piece {
        representation: 1,
        placements:     piece_find_placements(board, [0,  0,  1,  8, -1, -8], vec!((0,0), (0,1), (1,0), (1,1))),
    };
    let piece2 = Piece {
        representation: 2,
        placements:     piece_find_placements(board,  [0,  0,  1,  2,  7, 14], vec!((0,0), (0,1), (0,2), (0,3))) 
    };
    let piece3 = Piece {
        representation: 3,
        placements:     piece_find_placements(board,  [0,  0,  1,  2,  7,  6], vec!((0,0), (0,1), (0,2), (0,3), (1,0), (1,1), (1,2), (1,3))) 
    };
    let piece4 = Piece {
        representation: 4,
        placements:     piece_find_placements(board,  [0,  0,  1,  2, -1,  6], vec!((0,0), (0,1), (0,2), (0,3), (1,0), (1,1), (1,2), (1,3))) 
    };
    let piece5 = Piece {
        representation: 5,
        placements:     piece_find_placements(board,  [0,  0,  1,  7, -1, -2], vec!((0,0), (0,1), (0,2), (0,3), (1,0), (1,1), (1,2), (1,3))) 
    };
    let piece6 = Piece {
        representation: 6,
        placements:     piece_find_placements(board,  [0,  0,  1,  7,  6, -1], vec!((0,0), (0,1), (0,2), (0,3), (1,0), (1,1), (1,2), (1,3))) 
    };
    let piece7 = Piece {
        representation: 7,
        placements:     piece_find_placements(board,  [0,  0,  1,  8, -1 , 6], vec!((0,0), (0,1), (0,2), (0,3))) 
    };
    let piece8 = Piece {
        representation: 8,
        placements:     piece_find_placements(board,  [0,  1,  7,  8, -1,  6], vec!((0,0), (0,1))) 
    };

    return vec![piece1, piece2,piece3, piece4, piece5, piece6,piece7, piece8];
}

fn piece_find_placements(
    board: [u8; 49],
    relative_placement: [i8; 6],
    operations: Vec<(i8, i8)>,
) -> Vec<[i8; 6]> {
    let mut valid_placements = Vec::new();

    for start_cell in 0..49 as i8 {
        if board[start_cell as usize] > 0 {
            continue;
        }
        for operation in &operations {
            let placement = placement_operate(relative_placement, start_cell, *operation);
            if placement_test_on_boad(board, placement) {
                valid_placements.push(placement);
            }
        }
    }
    return valid_placements;
}

fn placement_operate(mut placement: [i8; 6], start_cell: i8, operation: (i8, i8)) -> [i8; 6] {
    for cell in placement.iter_mut() {
        for _i in 0..operation.0 {
            *cell = SYM[(*cell + 24) as usize];
        }
        for _i in 0..operation.1 {
            *cell = ROT[(*cell + 24) as usize];
        }
        *cell += start_cell;
    }
    placement.sort();
    return placement;
}

fn placement_test_on_boad(board: [u8; 49], placement: [i8; 6]) -> bool {
    return !(placement
        .iter()
        .any(|cell| *cell < 0 || *cell > 48 || board[*cell as usize] > 0)
        || (placement.contains(&20) && placement.contains(&21))
        || (placement.contains(&27) && placement.contains(&28))
        || (placement.contains(&34) && placement.contains(&35))
        || (placement.contains(&41) && placement.contains(&42)));
}

fn pieces_intersect_placement(pieces: &Vec<Piece>, placement: [i8; 6]) -> Vec<Piece> {
    let mut new_pieces = pieces.clone();
    for new_piece in new_pieces.iter_mut() {
        new_piece.placements = new_piece
            .placements
            .iter()
            .filter(|p| !placement_intersect_placement(**p, placement))
            .cloned()
            .collect();
    }
    return new_pieces;
}

fn placement_intersect_placement(p1: [i8; 6], p2: [i8; 6]) -> bool {
    return p1.iter().any(|x| p2.contains(x));
}
