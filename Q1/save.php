<?php
// 그룹 멤버 최소 인원
const MEMBER_MIN = 3;

// 그룹 멤버 최대 인원
const MEMBER_MAX = 10;

// 직업
const JOBS = ['과학자', '엔지니어', '조종사'];

/**
 * 구성원 생성
 *
 * @return array 구성원 배열
 */
function GetMember($job_idx) {
	// 그룹 최소 인원
	$groupMin = count(JOBS);

	// 그룹별로 직업이 최소 1명 포함되게 하기 위한 조건
	if($groupMin <= $job_idx) $job_idx = rand(0, $groupMin - 1);

	return [
	    'job' => JOBS[$job_idx],
	    'stat' => rand(1, 100),
	];	
}

/**
 * 그룹에 할당할 구성원 배열 생성
 *
 * @param int $memberMin 최소 인원 수
 * @param int $memberMax 최대 인원 수
 * @return array 구성원 배열
 */
function GetMembers($memberMin, $memberMax) {
    // 그룹에 포함할 인원수 랜덤 추출
    $numMembers = rand($memberMin, $memberMax); 
    
    $result = [];
    for($i = 0;$i < $numMembers;$i++) {
		$result[] = GetMember($i);
    }
    return $result;
}

/**
 * 그룹별 구성원 할당
 *
 * @param array $groups 그룹 배열 (ex: ['A', 'B', ...])
 * @return array 그룹별로 구성원 할당된 전체 배열
 */
function AssignGroups($groups) {
	$result = [];
	foreach($groups as $group) {
		$result[$group] = GetMembers(MEMBER_MIN, MEMBER_MAX);
	}
	return $result;
}

/**
 * 지원자 추출
 *
 * @param array $group 그룹 배열
 * @param array $remain_jobs 선택 가능한 남은 직업
 * @return array 승무원 배열
 */
function GetCandidates($group, $remain_jobs) {
	$candidates = [];
	foreach($group as $member) {
		if(in_array($member['job'], $remain_jobs)) {
			$candidates[] = $member;
		}
	}	
	return $candidates;
}

/**
 * 승무원 선택
 *
 * @param array $candidates 지원자 배열
 * @return array 승무원 배열
 */
function GetCrew($candidates) {
    $keys = array_keys($candidates);
    $randomKey = $keys[array_rand($keys)];
    return $candidates[$randomKey];	
}

/**
 * 선택 가능한 남은 직업 정리
 *
 * @param array $crew 이번에 선택한 승무원
 * @param array $remain_jobs 선택 가능한 남은 직업 (참조 호출)
 * @return array 승무원 배열
 */
function ArrangeRemainJobs($crew, &$remain_jobs) {
	if(count($remain_jobs) > 1) {
		$key = array_search($crew['job'], $remain_jobs);
		if ($key !== false) {
		    unset($remain_jobs[$key]);
		}   
	}
	else {
		$remain_jobs = JOBS;
	}
}

/**
 * 승무원 선택
 *
 * @param array $candidate_groups 지원자 그룹
 * @return array 구성원 배열
 */
function SelectCrews($candidate_groups) {
    // 직업별 최소 1명 구성을 위한 임시 배열
    $remain_jobs = JOBS;
	
    $crews = [];
    foreach($candidate_groups as $group) {
    	// 지원자 추출
	$candidates = GetCandidates($group, $remain_jobs);

	// 승무원 선택
        $crew = GetCrew($candidates);
        
        // 선택 가능한 남은 직업 정리
	ArrangeRemainJobs($crew, $remain_jobs);

	// 승무원 할당
        $crews[] = $crew;
    }
    return $crews;
}

/**
 * 생존 확률 계산
 *
 * @param array $crews 승무원 배열
 * @return float 생존 확률
 */
function CalculateSurvival($crews) {
    $total = 0;

    foreach ($crews as $crew) {
	$total += $crew['stat'];
    }
    return $total / (count($crews) * 100);
}

/**
 * 지원자 명단 출력
 *
 * @param array $candidates 지원자 배열
 */
function PrintCandidates($candidates) {
	echo "[지원자 명단]\n";
	foreach($candidates as $group => $members) {
		echo $group . " => ";
		echo json_encode($members, JSON_UNESCAPED_UNICODE);
		echo "\n";	
	}
}


// 샘플 실행
$groups = ['A', 'B', 'C', 'D'];

$candidates = AssignGroups($groups);
PrintCandidates($candidates);

$crews = SelectCrews($candidates);
echo "\n[최종 승무원]\n";
echo json_encode($crews, JSON_UNESCAPED_UNICODE);
echo "\n";

$survivalRate = CalculateSurvival($crews);
printf("\n[성공률] %0.1f%%", $survivalRate * 100);
