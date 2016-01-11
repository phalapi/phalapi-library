//
//  MyUtility.h
//  ThuInfo
//
//  Created by WenHao on 12-10-30.
//  Copyright (c) 2012年 WenHao. All rights reserved.
//

#import <Foundation/Foundation.h>

@interface MD5Encryption : NSObject
+ (NSString *)md5by32:(NSString*)input;
- (NSString *)md5:(NSString *)str;
@end
